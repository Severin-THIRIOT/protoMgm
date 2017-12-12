import Vue from 'vue'
import Router from 'vue-router'
import Index from '@/components/Index'
import Connection from '@/components/Connection'
import Items from '@/components/Items'
import AddList from '@/components/forms/AddList'
import AddItem from '@/components/forms/AddItem'
import AddParams from '@/components/forms/AddParams'
import EditItem from '@/components/forms/EditItem'
import EditList from '@/components/forms/EditList'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'index',
      component: Index,
      beforeEnter (to, from, next) {
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    },
    {
      path: '/addList',
      name: 'addList',
      component: AddList,
      beforeEnter (to, from, next) {
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    },
    {
      path: '/editList/:id(\\d+)',
      name: 'editList',
      component: EditList,
      props: true,
      beforeEnter (to, from, next) {
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    },
    {
      path: '/items/:id(\\d+)',
      name: 'items',
      component: Items,
      props: true,
      beforeEnter (to, from, next) {
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    },
    {
      path: '/addItem/:id(\\d+)',
      name: 'addItem',
      component: AddItem,
      props: true,
      beforeEnter (to, from, next) {
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    },
    {
      path: '/editItem/:listId(\\d+)/:id(\\d+)',
      name: 'editItem',
      component: EditItem,
      props: true,
      beforeEnter (to, from, next) {
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    },
    {
      path: '/connection',
      name: 'connection',
      component: Connection,
      beforeEnter (to, from, next) {
        if (sessionStorage.getItem('user')) {
          next('/')
        } else {
          next()
        }
      }
    },
    {
      path: '/addParams/:listId(\\d+)',
      name: 'addParams',
      component: AddParams,
      props: true,
      beforeEnter (to, from, next) {
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    },
    {
      path: '/*',
      name: 'allRoutes',
      beforeEnter (to, from, next) {
        console.log('user')
        if (!sessionStorage.getItem('user')) {
          next('/connection')
        } else {
          next()
        }
      }
    }
  ]
})
