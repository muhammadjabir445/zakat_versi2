export default {
    path:'/pembayaran-zakat',
    component:() => import('../views/index.vue'),
    meta:{auth:true},
    children:[
        {
            path:'',
            name:'dana.index',
            component : () => import('../views/pembayaran-zakat/index.vue')
        },
        {
            path:'create',
            name:'dana.create',
            component : () => import('../views/pembayaran-zakat/create.vue')
        },
        {
            path:':id/edit',
            name:'dana.edit',
            component: () => import('../views/pembayaran-zakat/edit.vue')
        }

    ]
}

