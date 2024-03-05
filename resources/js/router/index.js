import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'

/* Layouts */
const DahboardLayout = () => import('@/components/layouts/Default.vue')

/* Product */
const ProductList = () => import('@/components/customer/product/List.vue')
const ProductEdit = () => import('@/components/customer/product/Edit.vue')
const ProductAdd = () => import('@/components/customer/product/Add.vue')

/* Guest Component */
const Home = () => import('@/components/customer/Home.vue')

const routes = [
    {
      path: "/",
      component: DahboardLayout,
      meta: {
          middleware: "guest"
      },
      children: [
          {
              name: "home",
              path: '/',
              component: Home,
              meta: {
                  title: `Home`
              }
          },
          {
            name: "product",
            path: '/product',
            component: ProductList,
            meta: {
                title: `Products`
            }
          },
          {
            name: "productAdd",
            path: '/product/add',
            component: ProductAdd,
            meta: {
                title: `Products`
            }
          },
          {
            name: "productEdit",
            path: '/product/:id/edit',
            component: ProductEdit,
            meta: {
                title: `Products`
            }
          }
      ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title || 'Test Task';
  next();
});

export default router