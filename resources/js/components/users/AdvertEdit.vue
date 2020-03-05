<template>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">{{advert.title}}</div>

            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="row">
                        <div class="col-3">
                            <image-upload :data-image="'/storage/'+advert.filename" :model-file="advert.filename"></image-upload>
                        </div>
                        <div class="col-9">
                            <div class="form-group row">
                                <label for="title" class="col-4 col-form-label">Название</label>
                                <div class="col-8">
                                    <input class="form-control here" type="text" id="title" v-model="advert.title">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                advert:'',
            }
        },
        mounted() {
            axios.get('/api/v1/user/advert/edit/'+this.$route.params.id)
            .then(resp => (
                this.advert = resp.data
            )).catch(error => (
                this.error = error.data
            ))
        },
        methods: {
            submit(){

            }
        }
    }
</script>
