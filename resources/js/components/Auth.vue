<template>
    <div class="container mt-5" v-if="token === null">
      <div class="jumbotron">
        <form class="form-signin" @submit.prevent="userLogin()">
          <div class="alert alert-dark message-alert" hidden>
              <strong class="msg">Alert Message!</strong>
          </div>
          <h1 class="h3 mb-3 font-weight-normal text-center">Please Login</h1>
          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="inputEmail" class="form-control" v-model="login.email" placeholder="Email address" required autofocus>
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="inputPassword" class="form-control" v-model="login.password" placeholder="Password" required>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
        <div class="text-center">
          <a href="#" data-toggle="modal" data-target="#registerModal" > Sign Up</a>
        </div>
      </div>

        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registration</h5>
                <button @click="clearUser()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="userRegister()">
              <div class="modal-body">
                <div class="alert alert-dark message-alert" hidden>
                    <strong class="msg">Alert Message!</strong>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="first_name" class="col-form-label">First Name:</label>
                            <input type="text" class="form-control" id="first_name" v-model="register.first_name">
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-form-label">Last Name:</label>
                            <input type="text" class="form-control" id="last_name" v-model="register.last_name">
                        </div>
                        <div class="form-group">
                            <label for="username" class="col-form-label">Userame:</label>
                            <input type="text" class="form-control" id="username" v-model="register.username">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="email" v-model="register.email">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="password" class="col-form-label">Password:</label>
                            <input type="password" class="form-control" id="password" v-model="register.password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="col-form-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm_password" v-model="register.confirm_password">
                        </div>
                        <div class="form-group">
                            <label for="is_admin" class="col-form-label">Is Admin:</label>
                            <select class="custom-select" id="is_admin" v-model="register.is_admin">
                                <option selected value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Register</button>
              </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            login : {
                email: '',
                password: '',
            },
            register : {
                first_name: '', 
                last_name: '', 
                username: '', 
                is_admin: 0,
                email: '', 
                password: '',
                confirm_password: '',
            },
            token: localStorage.getItem('token') || null,
        }
    },

    methods: {
        userLogin() {
            fetch(`api/login`, {
                method : 'post',
                body : JSON.stringify(this.login),
                headers : {
                    'content-type' : 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                if(res.data.token) {
                  localStorage.setItem('token', "Bearer "+res.data.token);
                  localStorage.setItem('auth', JSON.stringify(res.data));
                  this.token = localStorage.getItem('token');
                  window.location.reload();
                } else {
                  this.alertMessage("Oops! " + res.data.message);
                }
            })
            .catch(err => this.alertMessage(err));
        },
        userRegister() {
          fetch(`api/register`, {
                method : 'post',
                body : JSON.stringify(this.register),
                headers : {
                    'content-type' : 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
              if (res.data.error) {
                this.alertMessage("Oops! validation error, please check all input fields.");
              } else {
                this.alertMessage("Nice! User created.");
                this.clearUser();
                $('#registerModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
              }
            })
            .catch(err => this.alertMessage(err));
        },
        clearUser() {
            this.register.first_name = ''; 
            this.register.last_name = ''; 
            this.register.username = ''; 
            this.register.is_admin = 0;
            this.register.email = ''; 
            this.register.password = '';
            this.register.confirm_password = '';
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
        },
    }
};
</script>

<style>

  .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
  }
  .form-signin .checkbox {
    font-weight: 400;
  }
  .form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
  }
  .form-signin .form-control:focus {
    z-index: 2;
  }
  .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
</style>