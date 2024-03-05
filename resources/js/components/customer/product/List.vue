<template>
  <div class="container dashboard">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header">
            <h2>Products</h2>
          </div>
          <div class="card-body d-grid gap-3">
            <span class="mb-0 fs-4"
              >You can Add, edit, delete and search Product.</span
            >
            <div class="cta-section">
              <router-link :to='{name:"productAdd"}' class="btn fs-4 btn-success">Add</router-link>
            </div>
            <div class="p-1 bg-light rounded rounded-pill shadow-sm mb-4">
                <div class="input-group">
                  <input v-model="term" type="search" placeholder="Search by: name, description" aria-describedby="button-addon1" class="form-control border-0 bg-light">
                  <div class="input-group-append">
                    <button id="button-addon1" type="btn" class="btn btn-link text-primary" @click="search"><i class="fa fa-search"></i></button>
                  </div>
                </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead class="table__head">
                  <tr class="winner__table">
                    <th>#</th>
                    <th><i class="fa fa-book" aria-hidden="true"></i> Name</th>
                    <th>
                      <i class="fa fa-user" aria-hidden="true"></i> Category
                    </th>
                    <th>
                      <i class="fa fa-money" aria-hidden="true"></i> Price
                    </th>
                    <th>
                      <i class="fa fa-barcode" aria-hidden="true"></i> Active
                    </th>
                    <th>
                      <i class="fa fa-book" aria-hidden="true"></i>
                      Description
                    </th>
                    <th>
                      <i class="fa fa-file-image-o" aria-hidden="true"></i> Image
                    </th>
                    <th>
                      <i class="fa fa-cogs" aria-hidden="true"></i>
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody v-if="products.data && products.data.length > 0">
                  <tr
                    v-for="product in products.data"
                    :key="product.id"
                    class="winner__table"
                  >
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ showCategoryName(product.category_id) }}</td>
                    <td>{{ product.price }}</td>
                    <td><span class="badge" :class="product.is_active ? 'badge-success' : 'badge-danger' ">{{ product.is_active ? 'Active' : 'InActive' }}</span></td>
                    <td>{{ product.description }}</td>
                    <td>{{ product.image }}</td>
                    <td>
                      <router-link :to='{name:"productEdit",params:{id:product.id}}' class="btn btn-success ml-1">Edit</router-link>
                      <button type="button" class="btn btn-danger ml-1" @click="deleteProduct(product.id)">Delete</button>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td align="center" colspan="3">No record found.</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <Bootstrap5Pagination
              class="mb-0"
              :data="products"
              :limit="limit"
              :keep-length="keepLength"
              :show-disabled="showDisabled"
              :size="size"
              :align="align"
              @pagination-change-page="getResults"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import 'bootstrap/dist/css/bootstrap.css';
import { Bootstrap5Pagination } from 'laravel-vue-pagination/src/lib';
export default {
  name:"products",
  components:{
    Bootstrap5Pagination
  },
  data(){
      return {
        processing:false,
        products: [],
        limit: 10,
        keepLength: false,
        showDisabled: false,
        size: 'default',
        align: 'left',
        term: '',
      }
  },
  mounted() {
    this.getProducts(1)
  },
  methods: {
    showCategoryName(categoryId) {
      if (categoryId === 1) {
        return "Category 1"
      } else if (categoryId === 2) {
        return "Category 2"
      } else {
        return "-"
      }
    },
    async getProducts(page){
        this.processing = true
        await axios.get(`/api/product?page=${page}&term=${this.term}`).then(({data})=>{
            this.products = data.data
        }).catch(({response})=>{

        }).finally(()=>{
            this.processing = false
        })
    },
    getResults(page){
      if (!page) {
        page = 1;
      }
      this.getProducts(page)
    },
    async deleteProduct(productId) {
      if(confirm("Are you sure to delete this product?")){
        this.processing = true
        await axios.delete('/api/product/'+productId).then(({data})=>{
            this.getProducts(1)
        }).catch(({response})=>{

        }).finally(()=>{
            this.processing = false
        })
      }
    },
    search(){
      this.getProducts(1)
    }
  }
}
</script>

<style scoped>
.table__head {
  color: #fff;
  font-weight: 700;
  background: #9b4085;
  background: -moz-linear-gradient(-45deg, #9b4085 0%, #608590 100%);
  background: -webkit-linear-gradient(-45deg, #9b4085 0%, #608590 100%);
  background: linear-gradient(135deg, #9b4085 0%, #608590 100%);
  white-space: nowrap;
}
.table-bordered td,
.table-bordered th {
  border: 0px solid #fff;
}
.winner__table {
  white-space: nowrap;
}

@media screen and (max-width: 567px) {
  .winner__table {
    font-size: 12px;
  }
}
</style>