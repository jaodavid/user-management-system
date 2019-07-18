<template>
	<div class="navbar navbar-expand-lg navbar-dark bg-dark mb-2">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="#">User Management System</a>
      </div>

      <div class="collapse navbar-collapse" id="navbarText" v-if="token !== null">
	    <ul class="navbar-nav mr-auto"></ul>
	    <a class="navbar-brand" href="#" @click="userLogout()">Logout</a>
	  </div>
    </div>
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