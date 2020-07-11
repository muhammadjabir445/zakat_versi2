<template>
    <v-app>
        <v-container>
            <v-btn small color="teal darken-2" class="white--text" tile>Tambah Mustahik</v-btn>
            <v-card
            class="border-edit"
            tile
            >
                <!-- <v-card-text class="text-center"> -->
                <v-card-text>
                    <v-container>
                        <v-form
                        ref="form"
                        v-model="valid"
                        :lazy-validation="lazy"
                        >
                        <label for="" align="left">Mustahik</label>

                        <v-text-field
                        v-model="ktp"
                        :rules="ktpRules"
                        label="No KTP"
                        required
                        ></v-text-field>

                        <v-text-field
                        v-model="nama"
                        :rules="namaRules"
                        label="Nama Lengkap"
                        required
                        ></v-text-field>

                       <v-textarea
                        v-model="alamat"
                        label="Alamat"
                        :rules="alamatRules"
                        required
                        ></v-textarea>

                        <v-row>
                            <v-col
                            cols="12"
                            align="right"
                            >
                              <v-btn
                                :disabled="!valid"
                                color="success"
                                tile
                                @click="save()"
                                :loading="loading"
                                >
                                Simpan
                                </v-btn>
                            </v-col>
                        </v-row>

                    </v-form>
                    </v-container>

                </v-card-text>

                <v-card-actions class="">

                </v-card-actions>
            </v-card>
        </v-container>
    </v-app>

</template>
<script>
// import {mapActions} from 'vuex'
import MustahikMixin from '../../mixins/MustahikMixin'
import middleware from '../../mixins/middleware'
export default {
    name: 'masterdata.edit',

    mixins:[MustahikMixin,middleware],
    methods:{
        async save(){
            this.loading = true
            let url = window.location.pathname
            url = url.split('/')
            url = "/" + url[1]
            let data = new FormData()
            data.append('nama',this.nama)
            data.append('alamat',this.alamat)
            data.append('ktp',this.ktp)

            await this.axios.post(url,data,this.config)
            .then((ress) => {
                console.log(ress)
                this.setSnakbar({
                    status:true,
                    pesan:ress.data.message,
                    color:'success'
                })
                this.$router.push(url)
            })
            .catch((err)=>{
                if (err.response.status == 400 ) {
                    this.setSnakbar({
                    color:'red',
                    status:true,
                    pesan:err.response.data.message,
                    })
                }else{
                    this.setSnakbar({
                    status:true,
                    color:'red',
                    pesan:"Terjadi Kesalahan",
                    })
                }

                console.log(err.response)
            })
            this.loading = false

        },

    }

}
</script>

<style lang="css" scoped>
</style>
