export default {
    path:'/dana-zakat',
    component:() => import('../views/index.vue'),
    meta:{auth:true},
    children:[
        {
            path:'',
            name:'dana.index',
            component : () => import('../views/dana-zakat/index.vue')
        },
        {
            path:'create',
            name:'dana.create',
            component : () => import('../views/dana-zakat/create.vue')
        },
        {
            path:':id/edit',
            name:'dana.edit',
            component: () => import('../views/dana-zakat/edit.vue')
        }

    ]
}

