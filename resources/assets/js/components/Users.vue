<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Users</div>

                    <div class="panel-body">
                        <a href="#" class="btn btn-primary pull-right btn-sm" @click.prevent="addUser">Add New User</a>
                        <br>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Name</th><th>Email</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users">
                                        <td>{{ user.id }}</td>
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-xs" @click.prevent="editUser(user)">Update</button>
                                             /
                                            <button class="btn btn-danger btn-xs" @click.prevent="deleteUser(user)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- User Form Modal -->
                <div class="modal fade" id="modal-create-user" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title">
                                    {{ form.editMode ? 'Edit User' : 'Create New User' }}
                                </h4>
                            </div>

                            <div class="modal-body">
                                <!-- Form Errors -->
                                <div class="alert alert-danger" v-if="form.errors.length > 0">
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="error in form.errors">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>

                                <!-- Create User Form -->
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Name</label>

                                        <div class="col-md-7">
                                            <input id="create-user-name" type="text" class="form-control" v-model="form.name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email</label>

                                        <div class="col-md-7">
                                            <input id="create-user-email" type="text" class="form-control" v-model="form.email">
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="!form.editMode">
                                        <label class="col-md-3 control-label">Password</label>

                                        <div class="col-md-7">
                                            <input id="create-user-password" type="password" class="form-control" v-model="form.password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Roles</label>

                                        <div class="col-md-7">
                                            <select id="create-user-roles" class="form-control" v-model="form.roles" multiple="true">
                                                <option v-for="role in roles" :value="role.name">{{ role.label }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Actions -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                <button v-if="form.editMode" type="button" class="btn btn-primary" @click.prevent="updateUser">
                                    Update
                                </button>
                                <button v-else type="button" class="btn btn-primary" @click.prevent="storeUser">
                                    Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                roles: [],
                users: [],
                form: {
                    editMode: false,
                    errors: [],
                    name: '',
                    email: '',
                    password: '',
                    roles: []
                }
            }
        },
        mounted() {
            this.getUsers();
        },
        methods: {
            getUsers() {
                this.$http.get('/admin/users').then(response => {
                    this.roles = response.data.roles;
                    this.users = response.data.users;
                });
            },
            addUser(user) {
                this.form.editMode = false;
                this.form.id = undefined;
                this.form.name = '';
                this.form.email = '';
                this.form.roles = [];

                $('#modal-create-user').modal('show');
            },
            editUser(user) {
                this.form.editMode = true;
                this.form.id = user.id;
                this.form.name = user.name;
                this.form.email = user.email;
                this.form.roles = user.roles;

                $('#modal-create-user').modal('show');
            },
            storeUser() {
                this.form.errors = [];

                this.$http.post('/admin/users', this.form).then(response => {
                    this.getUsers();

                    this.form.name = '';
                    this.form.email = '';
                    this.form.password = '';
                    this.form.roles = [];
                    this.form.errors = [];

                    $('#modal-create-user').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            updateUser() {
                this.form.errors = [];
                this.form.password = undefined;

                this.$http.put('/admin/users/' + this.form.id, this.form).then(response => {
                    this.getUsers();

                    this.form.name = '';
                    this.form.email = '';
                    this.form.password = '';
                    this.form.roles = [];
                    this.form.errors = [];
                    this.form.editMode = false;

                    $('#modal-create-user').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            deleteUser(user) {
                this.$http.delete('/admin/users/' + user.id).then(response => {
                    this.getUsers();
                });
            }
        }
    }
</script>
