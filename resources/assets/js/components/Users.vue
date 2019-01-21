<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users List</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal()">Add New <i class="fas fa-user-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Registered At</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type | upText }}</td>
                                    <td>{{ user.created_at | formattedDate }}</td>
                                    <td>
                                        <a href="#" class="blue" @click="editModal(user)"><i class="fas fa-edit"></i> Edit</a> /
                                        <a href="#" class="red" @click="deleteUser(user.id)"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode">Add New</h5>
                        <h5 class="modal-title" v-show="editmode">Update User's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name <span class="red">*</span></label>
                                <input v-model="form.name" type="text" name="name" class="form-control"
                                       placeholder="Name" :class="{ 'is-invalid': form.errors.has('username') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Email <span class="red">*</span></label>
                                <input v-model="form.email" type="text" name="email" class="form-control"
                                       placeholder="Email Address" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Type <span class="red">*</span></label>
                                <select v-model="form.type" name="type" class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="author">Author</option>
                                    <option value="user">User</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Bio</label>
                                <textarea v-model="form.bio" name="bio" class="form-control" placeholder="User's bio"
                                          :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Password <span class="red">*</span></label>
                                <input v-model="form.password" type="text" name="password" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-primary">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-success">Create</button>
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
                editmode: false,
                users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods: {
            loadUsers() {
                axios.get('api/user').then(({ data }) => ( this.users = data.data ) )
            },
            newModal() {
                this.editmode = false
                this.form.reset()
                $('#userModal').modal('show')
            },
            editModal(user) {
                this.editmode = true
                this.form.reset()
                $('#userModal').modal('show')
                this.form.fill(user)
            },
            createUser() {
                this.$Progress.start()
                this.form.post('api/user')
                    .then(() => {
                        Fire.$emit('AfterCreated')
                        $('#userModal').modal('hide')
                        toast({
                            type: 'success',
                            title: 'User Created successfully'
                        });
                        this.$Progress.finish()
                    })
                    .catch(() => {

                    })
            },
            updateUser(id) {
                this.$Progress.start()
                this.form.put('api/user/'+this.form.id)
                    .then(() => {
                        $('#userModal').modal('hide')
                        Swal('Updated!', 'Informations has been updated.', 'success')
                        this.$Progress.finish()
                        Fire.$emit('AfterCreated')
                    })
                    .catch(() => {
                        Swal('Failed!!', 'There was somthing wronge.', 'warning')
                    })
            },
            deleteUser(id) {
                Swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if(result.value)
                    {
                        this.form.delete('api/user/'+id)
                            .then(() => {
                                Swal('Deleted!', 'Your file has been deleted.', 'success')
                                Fire.$emit('AfterCreated')
                            })
                            .catch(() => {
                                Swal('Failed!!', 'There was somthing wronge.', 'warning')
                            })
                    }
                })
            }
        },
        created() {
            this.loadUsers();
            Fire.$on('AfterCreate', () => {
                this.loadUsers()
            });
        }
    }
</script>
