<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Roles</div>

                    <div class="panel-body">
                        <a href="#" class="btn btn-primary pull-right btn-sm" @click.prevent="addRole">Add New Role</a>
                        <br>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Name</th><th>Label</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="role in roles">
                                        <td>{{ role.id }}</td>
                                        <td>{{ role.name }}</td>
                                        <td>{{ role.label }}</td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-xs" @click.prevent="editRole(role)">Update</button>
                                             /
                                            <button class="btn btn-danger btn-xs" @click.prevent="deleteRole(role)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Role Form Modal -->
                <div class="modal fade" id="modal-create-role" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title">
                                    {{ form.editMode ? 'Edit Role' : 'Create New Role' }}
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

                                <!-- Create Role Form -->
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Name</label>

                                        <div class="col-md-7">
                                            <input id="create-role-name" type="text" class="form-control" v-model="form.name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Label</label>

                                        <div class="col-md-7">
                                            <input id="create-role-label" type="text" class="form-control" v-model="form.label">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Actions -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                <button v-if="form.editMode" type="button" class="btn btn-primary" @click.prevent="updateRole">
                                    Update
                                </button>
                                <button v-else type="button" class="btn btn-primary" @click.prevent="storeRole">
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
                form: {
                    editMode: false,
                    errors: [],
                    name: '',
                    label: ''
                }
            }
        },
        mounted() {
            this.getRoles();
        },
        methods: {
            getRoles() {
                this.$http.get('/admin/roles').then(response => {
                    this.roles = response.data;
                });
            },
            addRole(role) {
                this.form.editMode = false;
                this.form.id = undefined;
                this.form.name = '';
                this.form.label = '';

                $('#modal-create-role').modal('show');
            },
            editRole(role) {
                this.form.editMode = true;
                this.form.id = role.id;
                this.form.name = role.name;
                this.form.label = role.label;

                $('#modal-create-role').modal('show');
            },
            storeRole() {
                this.form.errors = [];

                this.$http.post('/admin/roles', this.form).then(response => {
                    this.getRoles();

                    this.form.name = '';
                    this.form.label = '';
                    this.form.errors = [];

                    $('#modal-create-role').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            updateRole() {
                this.form.errors = [];

                this.$http.put('/admin/roles/' + this.form.id, this.form).then(response => {
                    this.getRoles();

                    this.form.name = '';
                    this.form.label = '';
                    this.form.errors = [];
                    this.form.editMode = false;

                    $('#modal-create-role').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            deleteRole(role) {
                this.$http.delete('/admin/roles/' + role.id).then(response => {
                    this.getRoles();
                });
            }
        }
    }
</script>
