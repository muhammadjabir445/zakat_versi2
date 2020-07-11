<template>
    <v-app>
        <v-container>
            <v-btn small color="teal darken-2" class="white--text" tile>Tambah Penyalur Zakat</v-btn>
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
                        <label for="" align="left">Setting Aplikasi</label>

                        <v-text-field
                        v-model="harga_beras"
                        :rules="hargaRules"
                        label="Harga Beras per KG"
                        required
                        ></v-text-field>

                        <v-text-field
                        v-model="harga_emas"
                        :rules="hargaRules"
                        label="Harga Emas per Gram"
                        required
                        ></v-text-field>

                        <v-text-field
                        v-model="harga_perak"
                        :rules="hargaRules"
                        label="Harga Perak per Gram"
                        required
                        ></v-text-field>

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
import {mapActions} from 'vuex'
import middleware from '../../mixins/middleware'
export default {
    name: 'setting.edit',
    data: () => ({
        valid: true,
        lazy:false,
        loading:false,

        harga_emas: '',
        harga_beras: '',
        harga_perak: '',
        hargaRules: [
        v => !!v || 'Tidak Boleh Kosong',
        v =>  /^\d+$/.test(v) || 'Harus angka',
        v => v >= 1 || 'Format Salah'
        ],
      }),

    mixins:[middleware],
    methods:{
            ...mapActions({
            setSnakbar: 'snakbar/setSnakbar'
        }),
        async save(){
            this.loading = true
            let url = this.$route.path
            let data = new FormData()
            data.append('harga_beras',this.harga_beras)
            data.append('harga_perak',this.harga_perak)
            data.append('harga_emas',this.harga_emas)
            await this.axios.post(url,data,this.config)
            .then((ress) => {
                console.log(ress)
                this.setSnakbar({
                    status:true,
                    pesan:'Berhasil Ubah Data',
                    color:'success'
                })
            })
            .catch((err)=>{
                console.log(err)
            })
            this.loading = false

        },

        async go(){
            let url = this.$route.path
            await this.axios.get(url,this.config)
            .then((ress)=> {
                this.harga_emas = ress.data.harga_emas
                this.harga_perak = ress.data.harga_perak
                this.harga_beras = ress.data.harga_beras

            })
            .catch((err) =>{
                console.log(err.response)
            })
        }

    },
    created () {
        this.go()
    }

}
</script>

<style lang="css" scoped>
</style>
