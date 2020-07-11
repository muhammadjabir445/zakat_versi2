export default {
    path:'/penyalur-zakat',
    component:() => import('../views/index.vue'),
    meta:{auth:true},
    children:[
        {
            path:'',
            name:'users.index',
            component : () => import('../views/penyalur/index.vue')
        },
        {
            path:'create',
            name:'users.create',
            component : () => import('../views/penyalur/create.vue')
        },
        {
            path:':id/edit',
            name:'users.edit',
            component: () => import('../views/penyalur/edit.vue')
        }

    ]
}

