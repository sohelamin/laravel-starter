<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Permissions</div>

                    <div class="panel-body">
                        <a href="#" class="btn btn-primary pull-right btn-sm" @click.prevent="addPermission">Add New Permission</a>
                        <a href="#" class="btn btn-success btn-sm" @click.prevent="grantPermission">Give Permission to Role</a>
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
                                    <tr v-for="permission in permissions">
                                        <td>{{ permission.id }}</td>
                                        <td>{{ permission.name }}</td>
                                        <td>{{ permission.label }}</td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-xs" @click.prevent="editPermission(permission)">Update</button>
                                             /
                                            <button class="btn btn-danger btn-xs" @click.prevent="deletePermission(permission)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Permission Form Modal -->
                <div class="modal fade" id="modal-create-permission" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title">
                                    {{ form.editMode ? 'Edit Permission' : 'Create New Permission' }}
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

                                <!-- Create Permission Form -->
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Name</label>

                                        <div class="col-md-7">
                                            <input id="create-permission-name" type="text" class="form-control" v-model="form.name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Label</label>

                                        <div class="col-md-7">
                                            <input id="create-permission-label" type="text" class="form-control" v-model="form.label">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Actions -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                <button v-if="form.editMode" type="button" class="btn btn-primary" @click.prevent="updatePermission">
                                    Update
                                </button>
                                <button v-else type="button" class="btn btn-primary" @click.prevent="storePermission">
                                    Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permission Grant Form Modal -->
                <div class="modal fade" id="modal-grant-permission" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title">
                                    Give Permission to Role
                                </h4>
                            </div>

                            <div class="modal-body">
                                <!-- Form Errors -->
                                <div class="alert alert-danger" v-if="grantForm.errors.length > 0">
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="error in grantForm.errors">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>

                                <!-- Grant Permission Form -->
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Role</label>

                                        <div class="col-md-7">
                                            <select class="form-control" v-model="grantForm.role">
                                                <option v-for="role in roles" :value="role.name">{{ role.label }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Permissions</label>

                                        <div class="col-md-7">
                                            <select class="form-control" v-model="grantForm.permissions" multiple="true">
                                                <option v-for="permission in permissions" :value="permission.name">{{ permission.label }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Actions -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                <button type="button" class="btn btn-primary" @click.prevent="storeGrantPermission">
                                    Grant
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
                permissions: [],
                form: {
                    editMode: false,
                    errors: [],
                    name: '',
                    label: ''
                },
                grantForm: {
                    errors: [],
                    role: '',
                    permissions: []
                }
            }
        },
        mounted() {
            this.getPermissions();
            this.grantForm.role = (this.roles[0]) ? this.roles[0].name : '';
        },
        methods: {
            getPermissions() {
                axios.get('/admin/permissions').then(response => {
                    this.roles = response.data.roles;
                    this.permissions = response.data.permissions;
                });
            },
            addPermission(permission) {
                this.form.editMode = false;
                this.form.id = undefined;
                this.form.name = '';
                this.form.label = '';

                $('#modal-create-permission').modal('show');
            },
            editPermission(permission) {
                this.form.editMode = true;
                this.form.id = permission.id;
                this.form.name = permission.name;
                this.form.label = permission.label;

                $('#modal-create-permission').modal('show');
            },
            storePermission() {
                this.form.errors = [];

                axios.post('/admin/permissions', this.form).then(response => {
                    this.getPermissions();

                    this.form.name = '';
                    this.form.label = '';
                    this.form.errors = [];

                    $('#modal-create-permission').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            updatePermission() {
                this.form.errors = [];

                axios.put('/admin/permissions/' + this.form.id, this.form).then(response => {
                    this.getPermissions();

                    this.form.name = '';
                    this.form.label = '';
                    this.form.errors = [];
                    this.form.editMode = false;

                    $('#modal-create-permission').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            deletePermission(permission) {
                axios.delete('/admin/permissions/' + permission.id).then(response => {
                    this.getPermissions();
                });
            },
            grantPermission(permission) {
                this.grantForm.role = this.roles[0].name;
                this.grantForm.permissions = [];

                $('#modal-grant-permission').modal('show');
            },
            storeGrantPermission() {
                this.form.errors = [];

                axios.post('/admin/give-role-permissions', this.grantForm).then(response => {
                    this.grantForm.role = this.roles[0].name;
                    this.grantForm.permissions = [];
                    this.grantForm.errors = [];

                    $('#modal-grant-permission').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        this.grantForm.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.grantForm.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
        }
    }
</script>
