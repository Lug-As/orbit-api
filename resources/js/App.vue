<template>
    <div class="container">
        <ivan-component></ivan-component>
    </div>
</template>

<script>
import Ivan from './components/Ivan';

export default {
    name: 'App',
    data() {
        return {
            token: ''
        }
    },
    components: {
        ivanComponent: Ivan
    },
    mounted() {
        axios.post('/api/v1/login', {
            email: 'skal.04@mail.ru',
            password: '123123123'
        }).then(response => {
            this.token = response.data.token
            axios({
                method: 'get',
                url: '/api/v1/user',
                headers: {
                    Authorization: `Bearer ${this.token}`,
                },
            }).then(response => {
                console.log(response.data)
            })
        }).catch(error => console.log(error));
    }
}
</script>

<style scoped>

</style>
