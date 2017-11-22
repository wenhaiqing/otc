    import VueRouter from 'vue-router';

    let routes = [
        {
            path:'/',
            component: require('./components/Home')
        }
    ];

    export default new VueRouter({
        routes
    })