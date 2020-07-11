import Vue from 'vue'
import Router from 'vue-router'
import store from './stores'
import axios from 'axios'

import UserRouter from './routes/users'
import MasterDataRouter from './routes/Masterdata'
import MenuRouter from './routes/Menu'
import RoleManagementRouter from './routes/RoleManagement'
import PenyalurRouter from './routes/Penyalur'
import DanaZakat from './routes/Dana_zakat'
import PembayaranZakat from './routes/Pembayaran_zakat'
import MustahikRouter from './routes/Mustahik'

// import Vuetify from 'vuetify'
// Vue.use(Vuetify)
import './plugins/vuetify.js'
Vue.use(Router)
const router = new Router({
  mode: 'history',
  routes: [
    {
        path:'',
        name:'index',
        component:()=>import('./views/index.vue'),
        children:[
            {
                path: '',
                name: 'landing',
                component:()=>import('./views/landing.vue'),
            },
            {
                path: '/dahsboard',
                name: 'dashboard',
                component:()=>import('./views/dashboard.vue'),
                meta:{auth:true},
                beforeEnter: (to, from, next) => {
                    let user = store.getters['auth/user']
                    if (user.id_role == 36) {
                        next('/dana-zakat')
                    } else {
                        next()
                    }
                }

            },
            {
                path: '/setting-aplikasi',
                name: 'setting.aplikasi',
                component:()=>import('./views/setting-aplikasi/index.vue'),
                meta:{auth:true},
            },
            {
                path: '/kalkulator-zakat',
                name: 'kalkulator',
                component:()=>import('./views/kalkulator.vue'),

            },
            {
                path: '/bayar-zakat',
                name: 'bayar.zakat',
                component:()=>import('./views/bayar.vue'),

            },
            {
                path: '/cek-pembayaran',
                name: 'cek.pembayaran',
                component:()=>import('./views/cek_pembayaran.vue'),

            },
            {
                path: '/laporan-pembagian',
                name: 'dashboard',
                component:()=>import('./views/laporan-pembagian/index.vue'),
                meta:{auth:true}

            },
            {
                path: '/laporan-pembayaran',
                name: 'dashboard',
                component:()=>import('./views/laporan-pembayaran/index.vue'),
                meta:{auth:true}

            },

            UserRouter,
            MasterDataRouter,
            MenuRouter,
            RoleManagementRouter,
            PenyalurRouter,
            DanaZakat,
            PembayaranZakat,
            MustahikRouter
        ]

    },


    {
        path: '/login',
        name: 'login',
        component:()=>import('./views/Login.vue')
    },
    {
        path: '/404',
        name: 'notfound',
    },

    {
      path: '*',
      redirect: {
        name: 'notfound'
      }
    },

  ]
})



router.beforeEach(async (to,from,next) => {
    if( to.name != 'login') store.dispatch('BeforeUrl/setUrl',to.path)
    if (to.matched.some(record => record.meta.auth)) {

        if (store.getters['auth/user']) {
            next()
        }else{
            next('/login')
        }
    }else if(to.name == 'login'){
        if (!store.getters['auth/user']) {
            next()
        }else{
            router.push(store.getters['BeforeUrl/url'])

        }
    }else{
        next()
    }
})
// router.beforeEach((to,from,next) =>{
//     if(to.matched.some(record => record.meta.auth)){
//         //cek jika ada users maka kehalaman yg di tuju
//         if (store.getters['auth/user']) {
//             next()
//         }else{
//             next('/login')
//         }
//     }else if(to.name == 'login'){
//         //cek jika tidak ada users maka kehalaman login
//         if (!store.getters['auth/user']) {
//             next()
//         }else{

//             next('/dashboard')
//         }
//     }
//     else{
//         next()
//     }
// })
export default router
