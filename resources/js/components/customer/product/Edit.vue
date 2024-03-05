<template>
  <div class="container dashboard">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header">
            <h2>Edit Product Info</h2>
          </div>
          <div class="card-body d-grid gap-3">
            <form action="javascript:void(0)" method="post">
              <div
                class="col-12"
                v-if="Object.keys(validationErrors).length > 0"
              >
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    <li v-for="(value, key) in validationErrors" :key="key">
                      {{ value[0] }}
                    </li>
                  </ul>
                </div>
              </div>
              <div class="form-group mb-3">
                <label for="name" class="mb-1">Name</label>
                <input
                  type="name"
                  id="name"
                  class="form-control form-control-md"
                  placeholder="Enter product name"
                  v-model="form.name"
                />
              </div>
              <div class="form-group mb-3">
                <label for="category" class="mb-1">Category</label>
                <select v-model="form.category_id" class="form-control form-control-md">
                  <option v-for="option in options" :value="option.value">
                    {{ option.text }}
                  </option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="price" class="mb-1">Price</label>
                <input
                  type="text"
                  id="price"
                  class="form-control form-control-md"
                  placeholder="Enter price"
                  v-model="form.price"
                />
              </div>
              <div class="form-group mb-3">
                <label for="description" class="mb-1">Description</label>
                <textarea id="description" class="form-control form-control-md" v-model="form.description" placeholder="Product Desctiption"></textarea>
              </div>
              <div class="form-check mb-3">
                <label for="active" class="mb-1 form-check-label">Active</label>
                <input
                  type="radio"
                  class="form-check-input"
                  value="1"
                  v-model="form.is_active"
                />
              </div>
              <div class="form-check mb-3">
                <label for="inactive" class="mb-1 form-check-label">In-Active</label>
                <input
                  type="radio"
                  class="form-check-input"
                  value="0"
                  v-model="form.is_active"
                />
              </div>
              <div class="d-flex justify-content-center">
                <button
                  type="button"
                  class="btn btn-success btn-block btn-lg gradient-custom-4 text-body"
                  :disabled="processing"
                  @click="update"
                >
                  {{ processing ? "Please wait" : "Update" }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import router from '@/router'
export default {
  name: "Edit",
  data() {
    return {
      form: {},
      options: [
        { text: 'Category 1', value: '1' },
        { text: 'Category 2', value: '2' }
      ],
      processing:false,
      validationErrors:{},
    }
  },
  mounted(){
    this.getProduct()
  },
  methods: {
    async getProduct() {
      await axios.get('/sanctum/csrf-cookie')
      await axios.get('/api/product/'+this.$route.params.id).then(({data})=>{
        if(data.status == 200){
          this.form = data.data
        }else{
          this.$router.push({name:"product"})
        }
      }).catch(({response})=>{
        this.$router.push({name:"product"})
      }).finally(()=>{

      })
    },
    async update() {
      if(confirm("Are you sure to update this product?")){
        this.processing = true
        await axios.post('/api/product/'+this.$route.params.id, this.form).then(({data})=>{
          if(data.status===401){
            this.validationErrors = {error:[data.message]}
          }else{
            router.push({name:'product'})
          }
        }).catch(({response})=>{
          if(response.status===422){
                this.validationErrors = response.data.errors
            }else{
                this.validationErrors = {}
                alert(response.data.message)
            }
        }).finally(()=>{
            this.processing = false
        })
      }
    }
  }
}
</script>
<style scoped>
.mx-t3 {
  margin-top: -3rem;
}
</style>