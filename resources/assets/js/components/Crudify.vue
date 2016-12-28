<script>
    export default {
        props: ['label', 'url', 'fields', 'columns'],
        data() {
            return {
                defaultForm: {},
                form: {
                    editMode: false
                },
                errors: [],
                items: []
            }
        },
        created() {
            var self = this
            this.fields.forEach(function(field, index) {
                self.form[field.name] = field.type == 'select' ? [] : '';
                self.defaultForm[field.name] = field.type == 'select' ? [] : '';
            });

            this.getItems();
        },
        methods: {
            getItems() {
                this.$http.get(this.url).then(response => {
                    this.items = response.data;
                });
            },
            addItem() {
                this.form = this.defaultForm;
                this.form.editMode = false;

                $('#modal-create-item').modal('show');
            },
            editItem(item) {
                this.form = item;
                this.form.editMode = true;

                $('#modal-create-item').modal('show');
            },
            storeItem() {
                var self = this;
                this.errors = [];

                this.$http.post(this.url, this.form).then(response => {
                    this.getItems();

                    $('#modal-create-item').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        self.errors = _.flatten(_.toArray(response.data));
                    } else {
                        self.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            updateItem() {
                var self = this;
                this.errors = [];

                this.$http.put(this.url + '/' + this.form.id, this.form).then(response => {
                    this.getItems();

                    $('#modal-create-item').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        self.errors = _.flatten(_.toArray(response.data));
                    } else {
                        self.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            deleteItem(item) {
                this.$http.delete(this.url + '/' + item.id).then(response => {
                    this.getItems();
                });
            }
        }
    }
</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ label }}</div>

                    <div class="panel-body">
                        <a href="#" class="btn btn-primary pull-right btn-sm" @click.prevent="addItem">Add New {{ label }}</a>
                        <br>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th v-for="column in columns">{{ column[0].toUpperCase() + column.slice(1) }}</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in items">
                                        <td>{{ item.id }}</td>
                                        <td v-for="column in columns">{{ item[column] }}</td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-xs" @click.prevent="editItem(item)">Update</button>
                                             /
                                            <button class="btn btn-danger btn-xs" @click.prevent="deleteItem(item)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Item Form Modal -->
                <div class="modal fade" id="modal-create-item" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title">
                                    {{ form.editMode ? 'Edit ' + label : 'Create New ' + label }}
                                </h4>
                            </div>

                            <div class="modal-body">
                                <!-- Form Errors -->
                                <div class="alert alert-danger" v-if="errors.length > 0">
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="error in errors">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>

                                <!-- Create/Update Item Form -->
                                <form class="form-horizontal" role="form">
                                    <div class="form-group" v-for="(field, field_index) in fields">
                                        <label class="col-md-3 control-label">{{ field.name[0].toUpperCase() + field.name.slice(1) }}</label>

                                        <div class="col-md-7">
                                            <input v-if="field.type == 'text'" class="form-control" v-model="form[field.name]">
                                            <input v-if="field.type == 'email'" type="email" class="form-control" v-model="form[field.name]">
                                            <input v-if="field.type == 'password'" type="password" class="form-control" v-model="form[field.name]">
                                            <select v-if="field.type == 'select'" class="form-control" multiple="true" v-model="form[field.name]">
                                                <option v-for="option in field.options" :value="option.name">{{ option.label }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Actions -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                <button v-if="form.editMode" type="button" class="btn btn-primary" @click.prevent="updateItem">
                                    Update
                                </button>
                                <button v-else type="button" class="btn btn-primary" @click.prevent="storeItem">
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
