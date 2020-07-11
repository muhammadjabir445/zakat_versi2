export default {
    path:'/mustahik',
    component:() => import('../views/index.vue'),
    meta:{auth:true},
    children:[
        {
            path:'',
            name:'dana.index',
            component : () => import('../views/mustahik/index.vue')
        },
        {
            path:'create',
            name:'dana.create',
            component : () => import('../views/mustahik/create.vue')
        },
        {
            path:':id/edit',
            name:'dana.edit',
            component: () => import('../views/mustahik/edit.vue')
        }

    ]
}

