import Vue from 'vue'
import VueRouter from 'vue-router'
// Pages
import Home from '../pages/Home'
import Register from '../pages/Auth/Register'
import Login from '../pages/Auth/Login'
import Search from '../pages/Open/Search'
import ForgotPassword from '../pages/Auth/ForgotPassword'
import ResetPasswordForm from '../pages/Auth/ResetPasswordForm'
import PageUser from '../pages/PagesUser/001_MyPage'
import PageMyBooks from '../pages/PagesUser/002_MyBooks'
import SearchAuth from '../pages/PagesUser/003_Search'
import AdminDashboard from '../pages/Admin/Dashboard'
import PageNotFound from '../pages/PagesError/404'
import VerifyMail from "@/pages/Auth/VerifyMail";

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      guest: true
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      guest: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      guest: true
    }
  },
  {
    path: "/verify/:hash",
    name: "Verify",
    props: true,
    component: VerifyMail,
    meta: {guest:true}
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: ForgotPassword,
    meta: {guest:true}
  },
  {
    path: '/reset-password/:token',
    name: 'reset-password-form',
    component: ResetPasswordForm,
    meta: {guest:true}
  },
  {
    path: '/search',
    name: 'search',
    component: Search,
    meta: {
      guest: true
    }
  },
  // USER ROUTES
  {
    path: '/my-page',
    name: 'my.page',
    component: PageUser,
    meta: {auth: true,is_user: true},
  },
  {
    path: '/my-books',
    name: 'my.books',
    component: PageMyBooks,
    meta: {auth: true,is_user: true},
  },
  {
    path: '/search-books',
    name: 'search.books',
    component: SearchAuth,
    meta: {auth: true,is_user: true},
  },
  // ADMIN ROUTES
  {
    path: '/admin',
    name: 'admin.dashboard',
    component: AdminDashboard,
    redirect: {name: 'admin.page'},
    meta: {auth: true, is_admin: true},
    children: [
      {
        path: "admin-page",
        name: "admin.page",
        component: AdminDashboard,
        meta: {auth: true, is_admin: true},
      }
    ]
  },
  {
    path: "/*",
    component: PageNotFound,
  },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('isLogged')
  const admin = localStorage.getItem('admin')
  if(to.matched.some(record => record.meta.auth)) {
    if (!loggedIn) {
      next({
        path: '/',
        params: { nextUrl: to.fullPath }
      })
    } else {
      if(to.matched.some(record => record.meta.is_admin)) {
        if(admin !== null){
          next()
        } else{
          next({ name: 'my.page'})
        }
      }else if (to.matched.some(record => record.meta.is_user)) {
        if (admin !== null) {
          next({name: 'admin.page'})
        }
        next()
      }
    }
  } else if(to.matched.some(record => record.meta.guest)) {
    if(!loggedIn){
      if (admin !== null && to.matched.some(record => record.meta.is_admin)) {
        next()
      } else if (admin === null && (to.matched.some(record => record.meta.is_user) || to.matched.some(record => record.meta.is_admin))) {
        next({name: 'home'})
      }
      next()
    }
    else{
      if (status !== null && to.matched.some(record => record.meta.is_user)) {
        next({ name: 'admin.page'})
      } else if (to.matched.some(record => record.meta.guest) && admin !== null) {
        next({ name: 'admin.page'})
      } else if (admin === null && to.matched.some(record => record.meta.is_admin)) {
        next({ name: 'my.page'})
      } else if (to.matched.some(record => record.meta.guest)) {
        if (status !== null) {
          next({ name: 'admin.page'})
        } else {
          next({ name: 'my.page'})
        }
      }
    }
  }else {
    next()
  }
})


export default router
