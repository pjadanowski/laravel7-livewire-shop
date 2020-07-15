<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h6>Example Component</h6>
                {{ user }}
                {{ response }}
                <p v-if="response">
                    if you are logged in and still see this message. Make sure that in sanctum.php (.env file) you have
                    added your domain to stateful domains property and in <br>
                    webpack.mix.js you change this line <br>
                    .browserSync('laravel-7-boilerplate.test');
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: null,
                response: null
            }
        },
        mounted() {
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data
                    console.log(response.data)
                })
                .catch(error => {
                    console.log(error.data)
                    this.response = error.response.data
                })
        }
    }
</script>
