<?php

namespace MarketPlex\Http\Controllers;

use Illuminate\Http\Request as StoreRequest;
use Illuminate\Support\Facades\DB;

use MarketPlex\Http\Requests;
use MarketPlex\Http\Controllers\Controller;

use Log;
use Auth;
use Validator as StoreValidator;
use Redirect as StoreRedirect;

use MarketPlex\Store;
use MarketPlex\User;
use MarketPlex\SaleTransaction as Sale;
use MarketPlex\Mailers\AppMailer;
use MarketPlex\Helpers\ContactProfileManager;

class StoreController extends Controller
{
    
    private $_viewData = [];
    private $_rules = [];
    private $delimiter_phone_number = '-';

    private $_vendor_name = 'MarketPlex';

	/**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->_viewData = [
            'user' => Auth::user(),
            'stores' => Store::all()
        ];

        $this->_rules = collect([
            'name' => 'required|unique:stores|max:30',
            'description' => 'max:1000'
        ]);
        $this->_vendor_name = config('app.vendor');
    }

    private function viewUserStore(array $data)
    {
        $storeAvailableCount = Store::getAvailebleCount();
        $storeAvailableWarning = 'You have created maximum allowed stores';
        if (Store::isUnlimited())
            $storeAvailableWarning = 'You are allowed to create unlimited stores.';
        else if ($storeAvailableCount > 0)
            $storeAvailableWarning = 'You can create ' . $storeAvailableCount . ' more store(s).';

        return view('add-store', $data)->withUser(Auth::user()->id)
                                ->withStores(Auth::user()->stores)
                                ->withStoresPaginated(Auth::user()->stores()->paginate(5))
                                ->withSubmitable(Store::isAuthUserAllowedToCreate())
                                ->withSingleStore( !Store::isStoreOwnsSubdomain() )
                                ->withStoreCountWarning( $storeAvailableWarning )
                                ->withAreaCodes(collect(ContactProfileManager::areaCodes()));
    }

    public function index()
    {
        $defaultData = [ 'phone_number' => [0, ''], 'address' =>  ContactProfileManager::decodeAddress('') ];
        return $this->viewUserStore($defaultData);
    }

    public function redirectUrl($site, $ssl = true)
    {
        return StoreRedirect::to('http'. ($ssl ? 's' : '') .'://' . $site);
    }

    public function showProducts(StoreRequest $request, Store $store)
    {
        if($request->ajax())
        {
            return response()->json($store->products);
        }
        // dd($store->products);
        return view('store-products')->withStore($store)
                                     ->withProducts($store->products()->paginate(15));
    }

    // unused
    public function showSoldProducts(Store $store)
    {
        $sales = Sale::latest()->with('productbills.product.store')
                                ->join('product_bills', 'sale_transactions.id', '=', 'product_bills.sale_transaction_id')
                                ->join('products', 'products.id', '=', 'product_bills.product_id')
                                ->join('stores', 'stores.id', '=', 'products.store_id')
                                ->select('products.*')->where('store_id', $store->id)->distinct()->paginate();
        return view('store-sold-products')->withStore($store)
                                     ->withSales($sales);
    }

    private function validator(array $data, array $rules)
    {
        return StoreValidator::make($data, $rules);
    }

    public function delete(Store $store)
    {
        if($store->isStoreDeleteAllowed() && !$store->canDelete())
        {
            flash()->error('Sorry! You must have at least one shop to continue. Please contact your ' . $this->_vendor_name . ' administrator for any query.');
            return redirect()->back();
        }
        if(!$store->delete())
            return redirect()->back()->withErrors(['Sorry! we could not find the store named (' . $store->name . '). Please contact your ' . $this->_vendor_name . ' administrator for any query.']);
        flash()->success('Store (' . $store->name . ') information will be removed when approved by authority. Please contact ' . $this->_vendor_name . ' administrator for further assistance.');
        return redirect()->route('user::stores');
    }

    public function update(Store $store)
    {
        $user = User::find($store->user_id);
        $phone_number = ContactProfileManager::decodePhoneNumber($store->phone_number ? $store->phone_number : $user->phone_number);
        $address = ContactProfileManager::decodeAddress($store->address ? $store->address : $user->address);
        // Log::info($address);
        return $this->viewUserStore(compact('store', 'phone_number', 'address'));
    }

    public function postUpdate(StoreRequest $request, Store $store)
    {
        $storeName = $request->input('store_name');

        $address = ContactProfileManager::encodeAddress($request->only(
            'mailing-address',
            'address_flat_house_floor_building',
            'address_colony_street_locality',
            'address_landmark',
            'address_town_city'
        ));

        $data = collect([
            'name' => $storeName,
            'description' => $request->input('description'),
            'address' => $address,
            'phone_number' => $request->input('code') . $this->delimiter_phone_number . $request->input('phone_number'),
        ]);

        $rules = $this->_rules;
        if($storeName == $store->name)
        {
            $data = $data->forget('name');
            $rules = $rules->forget('name');
        }
        $validator = $this->validator($data->toArray(), $rules->toArray());
        if ($validator->fails())
        {            
            return redirect()->back()->withErrors($validator->errors());
        }
        if($data->has('name'))
        {
            $store->name = $data['name'];
            $store->name_as_url = strtolower(str_replace(' ', '', $data['name'])); // trims out the spaces
            $store->name_as_url = str_replace('.', '', $store->name_as_url); // removes '.' character
        }
        $store->description = $data['description'];
        $store->address = $data['address'];
        $store->phone_number = $data['phone_number'];

        if(!$store->save())
            return redirect()->back()->withErrors(['The store (' . $store->name . ') update is failed!']);
        flash()->success('Store (' . $store->name . ') information will be updated when approved by authority. Please contact ' . $this->_vendor_name . ' administrator for further assistance.');
        return redirect()->route('user::stores');
    }

    public function create(StoreRequest $request)
    {
        $storeName = $request->input('store_name');

        $address = ContactProfileManager::encodeAddress($request->only(
            'mailing-address',
            'address_flat_house_floor_building',
            'address_colony_street_locality',
            'address_landmark',
            'address_town_city'
        ));


        $data = [
            'name' => $storeName,
            'description' => $request->input('description'),
            'address' => $address,
            'phone_number' => $request->input('code') . $this->delimiter_phone_number . $request->input('phone_number'),
        ];
        $validator = $this->validator($data, $this->_rules->toArray());
        if ($validator->fails())
        {            
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }        
        $store = Store::create([
            'name' => $storeName,
            'user_id' => Auth::user()->id,
            'name_as_url' => strtolower(str_replace('.', '', str_replace(' ', '', $storeName))),
            'description' => $data['description'],
            'address' => $data['address'],
            'phone_number' => $data['phone_number'],
            'status' => 'ON_APPROVAL',
        ]);
        if(!$store)
        {
            return redirect()->back()->withErrors(['Failed to create store named (' . $store->name . ')']);
        }
        flash()->success('Store (' . $store->name . ') information will be published when approved by the authority. Please contact ' . $this->_vendor_name . ' administrator for further assistance.');
        return redirect()->back();
    }

    public function createOnSignUp($name, $site, $business)
    {
        $keywords = preg_split("/[.]+/", $site);

        if(count($keywords) < 3)
        {
            session()->forget('site');
            session()->forget('store');  
            session()->forget('business');

            Auth::user()->delete();

            return response()->view('home', [ 'errors' => collect(['Failed to create store! Please check your shop name again.']) ]);       
        }

        $storeNameUrl = $keywords[0];
        $subdomain = $keywords[1];
        $domain = $keywords[2];

        $store = Store::create([
            'name' => $name,
            'user_id' => Auth::user()->id,
            'name_as_url' => $storeNameUrl,
            'store_type' => $business,
            'status' => 'ON_APPROVAL',
        ]);
        if(!$store)
        {
            session()->forget('site');
            session()->forget('store');  
            session()->forget('business');

            Auth::user()->delete();

            return response()->view('home', [ 'errors' => collect(['Failed to create store! Please check your shop name again.']) ]);  
        }
        return redirect()->route('user::vendor.dashboard');
    }

    private function redirectWithMergedApprovals(array $approvals, \Illuminate\Http\RedirectResponse $route)
    {
        return $route->withApprovals(session()->has('approvals') ? collect(session('approvals'))->merge($approvals)->toArray() : $approvals);
    }

    public function approvals()
    {
        $stores = collect(Store::whereStatus('ON_APPROVAL')->orWhere('status', 'REJECTED')->orWhere('status', 'APPROVED')->get())->pluck( 'id', 'name' );
        $approvals = [
            'stores' => [
                'type' => Store::class,
                'data' => $stores
            ]
        ];
        return $this->redirectWithMergedApprovals($approvals, redirect()->route('admin::approvals.manage'));
    }

    public function confirmApproval(StoreRequest $request, AppMailer $mailer, $id)
    {
        $store = Store::find($id);
        if(!$store)
            return redirect()->back()->withErrors(['Your requested store is not found to approve!']);
        if(!$request->has('confirmation-select'))
            return redirect()->back()->withErrors(['Invalid request of approval confirmation!']);

        switch($request->input('confirmation-select'))
        {
            case 'approve':
                $store->status = 'APPROVED';
                break;
            case 'reject':
                $store->status = 'REJECTED';
                break;
            case 'remove':
                $store->status = 'REMOVED';
        }
        
        if(!$store->save())
            return redirect()->back()->withErrors(['Failed to confirm store approval!']);
        flash()->success('Your have ' . strtolower($store->getStatus()) . ' store (' . $store->name . ').');
        // Sends approval mail to user who created the product
        $data['type'] = Store::class;
        $data['status'] = $store->getStatus();
        $data['item_name'] = $store->name;
        $data['created_at'] = $store->created_at;
        $mailer->sendEmailForApprovalNotificationTo($store->user, $data);
        return redirect()->back();
    }

    public function suggest($input)
    {
        $storeNames = Store::whereNameAsUrl(str_replace(' ', '', strtolower($input)))->get();
        if(!$storeNames->first())
            return response()->json([ 'store' => collect([]) ]);
        $storeNames = Store::suggest($input, 10);
        $suggestions = array();
        foreach ($storeNames as $name)
        {
            $storeNames = Store::whereNameAsUrl(str_replace(' ', '', strtolower($name)))->get();
            if(!$storeNames->first())
                $suggestions []= $name;
        } 
        $stores = empty($suggestions) ? $storeNames : $suggestions;
        return response()->json([ 'store' => collect($stores)->take(5) ]);
    }

    public function showSales(Store $store)
    {
        $sales = Sale::latest()->with('productbills.product.store')
                                ->join('product_bills', 'sale_transactions.id', '=', 'product_bills.sale_transaction_id')
                                ->join('products', 'products.id', '=', 'product_bills.product_id')
                                ->join('stores', 'stores.id', '=', 'products.store_id')
                                ->select('sale_transactions.*', 'products.store_id')->where('store_id', $store->id)->distinct()->paginate();
        return view('store-sales')->withStore($store)
                                  ->withSales($sales);
    }
}
