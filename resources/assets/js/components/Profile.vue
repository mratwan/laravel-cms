<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-widget widget-user" style="padding: 0">
                    <div class="widget-user-header text-white" style="background: url('/images/bg1.png') center center;background-size: cover;height: 250px;">
                        <h3 class="widget-user-username">Mohammadreza Atwan</h3>
                        <h5 class="widget-user-desc">Web Designer</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="loadProfilePhoto()" alt="User Avatar" style="width: 120px;height: 120px; border-radius: 50%;">
                    </div>
                    <div class="card-footer" style="padding: 0">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">Display User Activity</h3>
                            </div>
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" v-model="form.name" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-12">
                                            <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label">Experience</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" v-model="form.bio" id="inputExperience" placeholder="Experience" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                            <has-error :form="form" field="bio"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                        <div class="col-sm-12">
                                            <input id="photo" type="file" @change="updateProfile" name="photo" class="form-input" value="profile.png">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-sm-12 control-label">Password (leave empty if not changing)</label>
                                        <div class="col-sm-12">
                                            <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success" @click.prevent="updateInfo">Update</button>
                                        </div>
                                    </div>
                                </form>
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
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            updateInfo() {
                this.$Progress.start();
                if(this.form.password == '') {
                    this.form.password = undefined
                }
                this.form.put('api/profile')
                    .then(()=>{
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish()
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    });
            },
            updateProfile(e) {
                let file = e.target.files[0]
                let reader = new FileReader()
                let limit = 1024 * 1024 * 2
                if(file['size'] > limit){
                    Swal({ type: 'error', title: 'Oops...', text: 'You are uploading a large file' })
                    return false
                }
                reader.onloadend = () => {
                    this.form.photo = reader.result
                }
                reader.readAsDataURL(file)
            },
            loadProfilePhoto() {
                let photo = (this.form.photo.length > 200) ? this.form.photo : "images/avatars/"+ this.form.photo
                return photo
            }
        },
        created() {
            axios.get('api/profile').then(({ data }) => ( this.form.fill(data) ) )
        }
    }
</script>
