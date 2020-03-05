<template>
    <div class="Image-input">
        <div class="form-group">
            <img v-show="! imageSrc" class="img-fluid" :src="dataImage">
            <img v-show="imageSrc" class="img-fluid" :src="imageSrc">
        </div>

        <div class="form-group">
            <small v-show="filename">{{ filename }}</small>
            <label for="file_input" class="btn btn-primary">Выберите файл</label>
            <input @change="previewThumbnail" accept="image/*" class="Image-input__input btn-primary d-none" id="file_input" name="thumbnail" type="file" >
        </div>
    </div>
</template>

<script>
    export default {
        props: ['dataImage','modelFile' ],
        data: function(){
            return {
                imageSrc: '',
                filename: ''
            }

        },
        methods: {
            previewThumbnail: function(event) {
                var input = event.target;

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    var vm = this;

                    reader.onload = function(e) {
                        vm.imageSrc = e.target.result;
                        vm.filename = input.files[0].name;
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
    }
</script>

<style scoped>

</style>
