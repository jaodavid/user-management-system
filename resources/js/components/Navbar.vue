<template>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand text-light">User Management System</a>
        <div class="form-inline" v-if="token !== null">
            <a class="navbar-brand text-light"> Hi {{ auth.first_name }},</a>
            <a class="mr-sm-2 text-light" href="#" @click="userLogout()">Logout</a>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            auth: JSON.parse(localStorage.getItem('auth')) || null,
            token: localStorage.getItem('token') || null,
        }
    },

    methods: {
        userLogout() {
            fetch(`api/logout`, {
                method : 'get',
                headers : {
                    'content-type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : this.token
                }
            })
            .then(res => res.json())
            .then(res => {
                localStorage.removeItem('token');
                localStorage.removeItem('auth');
                window.location.reload();
            })
            .catch(err => this.alertMessage(err));
        },
        alertMessage(message) {
            $('.msg').text(message);
            $(".message-alert").removeAttr('hidden');
            window.setTimeout(function() {
                $(".message-alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).attr('hidden', 'hidden');
                    $(this).removeAttr("style")
                    $('.msg').text('');
                });
            }, 4000);
        }
    }
};
</script>