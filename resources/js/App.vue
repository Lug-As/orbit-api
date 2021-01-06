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
        return {}
    },
    components: {
        ivanComponent: Ivan
    },
    mounted() {
        // axios.get('/sanctum/csrf-cookie').then(response => {
        //     axios.post('/login', {
        //         email: 'skal.04@mail.ru',
        //         password: '123123123'
        //     }).then(response => {
        //         axios.get('/api/usr').then(response => {
        //             console.log(response.data)
        //         })
        //     }).catch(error => console.log(error));
        // })
    }
}
</script>

<style scoped>

</style>
