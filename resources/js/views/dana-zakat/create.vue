<template>
    <v-app>
        <v-container>
            <v-row>
                <v-col
                cols="12"
                md="6"
               v-if="jenis_pengajuan == 'Pembagian zakat'"
                >
                    <v-card
                    class="border-edit"
                    tile

                    >
                    <v-btn small color="teal darken-2" class="white--text" tile>Uang Yang Dapat Diajukan</v-btn>
                        <v-card-text class="text-center">
                        <h3>Rp.{{total_uang_sisa}}</h3>
                        </v-card-text>

                        <v-card-actions class="">

                        </v-card-actions>
                    </v-card>
                </v-col>

                 <v-col
                cols="12"
                md="6"
                v-if="jenis_pengajuan == 'Pembelian beras'"
                >
                    <v-card
                    class="border-edit"
                    tile

                    >
                    <v-btn small color="teal darken-2" class="white--text" tile>Uang Yang Dapat Diajukan</v-btn>
                        <v-card-text class="text-center">
                        <h3>Rp.{{total_uang_beras}}</h3>
                        </v-card-text>

                        <v-card-actions class="">

                        </v-card-actions>
                    </v-card>
                </v-col>

                <v-col
                cols="12"
                md="6"
                v-if="jenis_pengajuan == 'Pembagian zakat'"
                >
                    <v-card
                    class="border-edit"
                    tile

                    >
                    <v-btn small color="teal darken-2" class="white--text" tile>Beras Yang Dapat Diajukan</v-btn>
                        <v-card-text class="text-center">
                        <h3>{{total_beras_sisa}} Kg</h3>
                        </v-card-text>

                        <v-card-actions class="">

                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
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
                        <label for="" align="left">Pengajuan Dana Zakat</label>

                         <v-select
                            v-model="jenis_pengajuan"
                            :items="pilihan"
                            :rules="[v => !!v || 'Item is required']"
                            label="Jenis Pengajuan"
                            required
                        ></v-select>
                        <div v-if="jenis_pengajuan">
                             <v-select
                            v-model="nama_yayasan"
                            :items="lembaga"
                            :rules="[v => !!v || 'Item is required']"
                            label="Nama Yayasan/Lembaga"
                            item-text="nama"
                            item-value="id"
                            required
                            v-if="jenis_pengajuan == 'Pembagian zakat'"
                            ></v-select>

                            <v-text-field
                            v-model="uang"
                            :rules="[uangRules,uangValidasi(total_uang_beras,total_uang_sisa,jenis_pengajuan)]"
                            :label="jenis_pengajuan == 'Pembagian zakat' ? 'Uang yang diberikan' : 'Uang yang dibutuhkan'"
                            required
                            ></v-text-field>

                            <v-text-field
                            v-model="beras"
                            :rules="[berasValidasi(total_beras_sisa,jenis_pengajuan),berasRules]"
                            :label="jenis_pengajuan == 'Pembagian zakat' ? 'Beras yang diberikan' : 'Total beras yang dibeli'"

                            ></v-text-field>



                            <v-textarea
                            v-model="deskripsi"
                            label="Deskripsi"
                            required
                            ></v-textarea>

                        </div>



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
import danazakat from '../../mixins/danazakat'
import middleware from '../../mixins/middleware'
export default {
    name: 'masterdata.edit',

    mixins:[danazakat,middleware],
    methods:{
        async save(){
            this.loading = true
            let url = window.location.pathname
            url = url.split('/')
            url = "/" + url[1]
            let data = new FormData()
            data.append('nama_yayasan',this.nama_yayasan)
            data.append('beras',this.beras)
            data.append('uang',this.uang)
            data.append('deskripsi',this.deskripsi)

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

                    this.setSnakbar({
                    status:true,
                    color:'red',
                    pesan:"Terjadi Kesalahan",
                    })


                console.log(err.response)
            })
            this.loading = false

        },

    },
    created () {
         this.getRole()
    }

}
</script>

<style lang="css" scoped>
</style>
