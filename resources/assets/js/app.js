
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app'
// });

Echo.channel('client-action.marketplex.aarombor')
	.listen('.MarketPlex.Events.ClientAction', (e) => {

		// console.log("hello pusher");

		axios.get('http://127.0.0.1:5959/api/v1/app', {
		    firstName: 'Fred',
		    lastName: 'Flintstone'
		  })
		  .then(function (response) {
		    console.log(response);		
		    // alert(response);
		  })
		  .catch(function (error) {
		    console.log(error);	
		    // alert(error);
		  });

	});