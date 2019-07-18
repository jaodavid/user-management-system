<template>
    <div>
        <div class="container" v-if="token !== null && auth.is_admin === 1">
            <div class="alert alert-dark message-alert" hidden>
                <strong class="msg">Alert Message!</strong>
            </div>
            <div class="row">
                <div class="col-sm">
                    <button @click="deleteMultiple()" type="button" class="btn btn-outline-danger float-right m-1">Delete Multiple</button>
                    <button type="button" class="btn btn-outline-primary float-right m-1" data-toggle="modal" data-target="#userModal">Create New User</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <h3>User List</h3>
                    <table class="table table-dark">
                        <thead >
                          <tr>
                            <th></th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="user in users" v-bind:key="user.id">
                            <td><input type="checkbox" v-bind:value="user.id" v-model="checkedUser"></td>
                            <td>{{ user.first_name }}</td>
                            <td>{{ user.last_name }}</td>
                            <td>{{ user.email }}m</td>
                            <td>
                                <button @click="editUser(user)" type="button" class="btn btn-outline-info">View/Edit</button>
                                <button @click="deleteUser(user.id)" type="button" class="btn btn-outline-danger">Delete</button>
                            </td>
                          </tr>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                                <a class="page-link" href="#" @click="fetchUsers(pagination.prev_page_url)">Previous</a>
                            </li>

                            <li class="page-item disabled">
                                <a class="page-link text-dark" href="#">
                                    Page {{ pagination.current_page }} of {{ pagination.last_page }}
                                </a>
                            </li>

                            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                                <a class="page-link" href="#" @click="fetchUsers(pagination.next_page_url)">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel" v-if="edit">Edit User</h5>
                    <h5 class="modal-title" id="userModalLabel" v-else>Create User</h5>
                    <button @click="clearUser()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form @submit.prevent="saveUser()">
                  <div class="modal-body">
                    <div class="alert alert-dark message-alert" hidden>
                      <strong class="msg">Alert Message!</strong>
                  </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="first_name" class="col-form-label">First Name:</label>
                                <input type="text" class="form-control" id="first_name" v-model="user.first_name">
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-form-label">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" v-model="user.last_name">
                            </div>
                            <div class="form-group">
                                <label for="username" class="col-form-label">Userame:</label>
                                <input type="text" class="form-control" id="username" v-model="user.username">
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email:</label>
                                <input type="text" class="form-control" id="email" v-model="user.email">
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group" v-if="!edit">
                                <label for="password" class="col-form-label">Password:</label>
                                <input type="password" class="form-control" id="password" v-model="user.password">
                            </div>
                            <div class="form-group" v-if="!edit">
                                <label for="confirm_password" class="col-form-label">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirm_password" v-model="user.confirm_password">
                            </div>
                            <div class="form-group" v-if="edit">
                                <label for="address" class="col-form-label">Address:</label>
                                <input type="text" class="form-control" id="address" v-model="user.address">
                            </div>
                            <div class="form-group" v-if="edit">
                                <label for="postal_code" class="col-form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" v-model="user.postal_code">
                            </div>
                            <div class="form-group" v-if="edit">
                                <label for="contact_number" class="col-form-label">Contact Number:</label>
                                <input type="text" class="form-control" id="contact_number" v-model="user.contact_number">
                            </div>
                            <div class="form-group">
                                <label for="is_admin" class="col-form-label">Is Admin:</label>
                                <select class="custom-select" id="is_admin" v-model="user.is_admin">
                                    <option selected value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
        <div v-if="token !== null && auth.is_admin !== 1">
            <main role="main" class="container">
              <h1 class="mt-5">Oop! Unauthorized user</h1>
              <p class="lead">This is an Admin only page.</p>
            </main>
        </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users : [],
            user : {
                id: '',
                first_name: '', 
                last_name: '', 
                address: '', 
                postal_code: '', 
                contact_number: '', 
                username: '', 
                is_admin: 0,
                email: '', 
                password: '',
                confirm_password: '',
            },
            checkedUser: [],
            token: localStorage.getItem('token') || null,
            auth: JSON.parse(localStorage.getItem('auth')) || null,
            pagination : {},
            edit : false,
        }
    },

    created() {
        if(this.token !== null && this.auth.is_admin === 1) {
            this.fetchUsers();
        }
    },

    methods: {
        fetchUsers(page_url) {
            let vm = this;
            page_url = page_url || '/api/users'
            fetch(page_url, {
                method : 'get',
                headers : {
                    'content-type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : this.token
                }
            })
            .then(res => res.json())
            .then(res => {
                this.users = res.data;
                vm.makePagination(res.meta, res.links);
            })
            .catch(err => this.alertMessage(err));
        },
        makePagination(meta, links) {
            let pagination = {
                current_page : meta.current_page,
                last_page : meta.last_page,
                next_page_url : links.next,
                prev_page_url : links.prev
            }

            this.pagination = pagination;
        },
        saveUser() {
            if (this.edit === false) {
                fetch(`api/user/`, {
                    method : 'post',
                    body : JSON.stringify(this.user),
                    headers : {
                        'content-type' : 'application/json',
                        'Accept' : 'application/json',
                        'Authorization' : this.token
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.data.message) {
                        this.alertMessage(res.data.message);
                    } else if (res.data.error) {
                        this.alertMessage("Oops! validation error, please check all input fields.");
                    } else {
                        this.clearUser();
                        $('#userModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        this.alertMessage('Nice! User created!');
                        this.fetchUsers();
                    }
                })
                .catch(err => this.alertMessage(err));
            } else {
                fetch(`api/user/`, {
                    method : 'put',
                    body : JSON.stringify(this.user),
                    headers : {
                        'content-type' : 'application/json',
                        'Accept' : 'application/json',
                        'Authorization' : this.token
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.data.message) {
                        this.alertMessage(res.data.message);
                    } else if (res.data.error) {
                        this.alertMessage("Oops! validation error, please check all input fields.");
                    } else {
                        this.clearUser();
                        $('#userModal').modal('hide');
                        this.alertMessage('Nice! User updated!');
                        this.fetchUsers();
                    }
                    this.edit = !this.edit;;
                })
                .catch(err => this.alertMessage(err));
            }
        },
        deleteUser(id) {
            if (confirm("Are you sure?")) {
                fetch(`api/user/${id}`, {
                    method : 'delete',
                    headers : {
                        'content-type' : 'application/json',
                        'Accept' : 'application/json',
                        'Authorization' : this.token
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.data.message) {
                        this.alertMessage(res.data.message);
                        this.fetchUsers();
                    } else {
                        this.alertMessage('Nice! User deleted!');
                        this.fetchUsers();
                    }
                })
                .catch(err => this.alertMessage(err));
            }
        },
        deleteMultiple() {
            fetch(`api/users/delete-multiple`, {
                method : 'post',
                body : JSON.stringify({ids: this.checkedUser}),
                headers : {
                    'content-type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : this.token
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.data.message) {
                    this.alertMessage(res.data.message);
                } else if (res.data.error) {
                    this.alertMessage("Oops! Please select user/s to delete by ticking the checkbox beside their names.");
                } else {
                    this.alertMessage('Nice! User/s deleted!');
                    this.fetchUsers();
                }
            })
            .catch(err => this.alertMessage(err));
        },
        editUser(user) {
            this.edit = true;
            this.user.id = user.id;
            this.user.first_name = user.first_name; 
            this.user.last_name = user.last_name; 
            this.user.address = user.address; 
            this.user.postal_code = user.postal_code; 
            this.user.contact_number = user.contact_number; 
            this.user.username = user.username; 
            this.user.is_admin = user.is_admin;
            this.user.email = user.email; 
            this.user.password = user.password;
            this.user.confirm_password = user.confirm_password;
            $('#userModal').modal('show');
        },
        clearUser() {
            this.user.id = '';
            this.user.first_name = ''; 
            this.user.last_name = ''; 
            this.user.address = ''; 
            this.user.postal_code = ''; 
            this.user.contact_number = ''; 
            this.user.username = ''; 
            this.user.is_admin = 0;
            this.user.email = ''; 
            this.user.password = '';
            this.user.confirm_password = '';
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