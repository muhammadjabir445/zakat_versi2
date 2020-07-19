<template>
    <v-app>
        <v-container>
            <v-btn small color="teal darken-2" class="white--text" tile>Tambah Pembayaran Zakat</v-btn>
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
                        <label for="" align="left">Pembayaran Zakat</label>

                        <v-select
                            v-model="jenis_zakat"
                            :items="items"
                            :rules="[v => !!v || 'Item is required']"
                            label="Jenis Zakat"
                            item-text="description"
                            item-value="id"
                            required
                        ></v-select>

                        <v-select
                            v-model="jenis_pembayaran"
                            :items="pilihan"
                            :rules="[v => !!v || 'Item is required']"
                            label="Jenis Pembayaran"
                            required
                            v-if="jenis_zakat == 38 || jenis_zakat == 44"
                        ></v-select>

                        <v-text-field
                        v-model="nama"
                        :rules="namaRules"
                        label="Nama Muzaki"
                        required
                        ></v-text-field>

                        <v-text-field
                        v-model="email"
                        :rules="emailRules"
                        label="Email"
                        required
                        ></v-text-field>

                        <v-text-field
                        v-model="nohp"
                        :rules="nohpRules"
                        label="No HP"
                        required
                        ></v-text-field>

                        <v-text-field
                        v-if="(jenis_zakat == 38 || jenis_zakat == 44) && jenis_pembayaran == 'Uang'"
                        :label="jenis_zakat == 38 ? 'jumlah orang' : (jenis_zakat == 44 ? 'Total Beras' : '')"
                        v-model="jumlah_orang"
                        :rules="orangRules"
                        required
                        ></v-text-field>

                        <v-text-field
                        v-model="jumlah_transfer"
                        :rules="jumlahRules"
                        :disabled="(jenis_zakat == 38 || jenis_zakat == 44) && jenis_pembayaran == 'Uang'"
                        :label="jenis_pembayaran == 'Beras' ? 'Total Beras' : 'Total Pembayaran'"
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
    name: 'dsf.edit',
    data: () => ({
        valid: true,
        lazy:false,
        loading:false,
        jenis_zakat: '',
        items:[],
        nama: '',
        orang: 0,
        nohp: '',
        email: '',
        foto:'',
        data:'',
        harga_beras:'',
        jenis_pembayaran:'',
        pilihan: ['Beras', 'Uang'],

        emailRules: [
            v => /.+@.+\..+/.test(v) || 'E-mail tidak valid',
            v => !!v || 'Tidak Boleh Kosong',
        ],
        nohpRules: [
            v => !!v || 'Tidak Boleh Kosong',
            v => v.length <= 13 || 'Maksimal panjang 13',
            v => /^\d+$/.test(v) || 'Harus angka'
        ],
        orangRules: [
            v => !!v || 'Tidak Boleh Kosong',
            v => v > 0 || 'Minimal 1',
            v => /^\d+$/.test(v) || 'Harus angka'
        ],
        namaRules: [
          v => !!v || 'Tidak Boleh Kosong',
        ],
        jumlah_transfer:null,
        jumlahRules:[
          v => !!v || 'Harus diisi',
        //   v => /^\d*\.?(?:\d{1,2})?$/.test(v) || 'digit cuma 2'
          v => /^\d*\.?(?:\d{1,1000000000})?$/.test(v) || 'Format Salah'
        ],
      }),
    mixins:[middleware],
    methods:{
        ...mapActions({
            setSnakbar: 'snakbar/setSnakbar'
        }),
        async save(){
            this.loading = true
            let url = window.location.pathname
            url = url.split('/')
            url = "/" + url[1]
            let data = new FormData()
            data.append('jenis_zakat',this.jenis_zakat)
            data.append('nama',this.nama)
            data.append('email',this.email)
            data.append('nohp',this.nohp)
            data.append('jumlah_transfer',this.jumlah_transfer)

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

                console.log(err)
            })
            this.loading = false

        },

    },

    computed:{
        jumlah_orang: {
              get() {
                  return this.orang
              },
              set(newValue) {
                  this.orang = newValue
                  this.jumlah_transfer = this.harga_beras * this.orang
                  if (this.jenis_zakat == 38) {
                      this.jumlah_transfer = this.jumlah_transfer * 3
                  }
                  console.log(this.jumlah_transfer)
                  console.log(this.orang)
                  console.log(this.harga_beras)
              }
          }
    },
    async created(){
         let url = '/jenis-zakat'
          await this.axios.get(url)
          .then((ress) => {
              console.log(ress)
              this.items = ress.data.jenis_zakat
              this.harga_beras = ress.data.harga.harga_beras
          })
          .catch((err) => {
              console.log(err.response)
          })
    }

}
</script>

<style lang="css" scoped>
</style>
