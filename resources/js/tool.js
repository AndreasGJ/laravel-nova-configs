Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'laravel-nova-configs',
            path: '/laravel-nova-configs',
            component: require('./components/Tool'),
        },
    ])
})
