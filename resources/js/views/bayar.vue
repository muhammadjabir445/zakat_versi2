<template>
    <v-app>
        <v-row justify="center">
            <v-col
            cols="12"
            md="4"
            >
                <v-container>
                <v-card
                class="border-edit mb-3"
                tile
                >
                <v-btn small color="teal darken-2" class="white--text" tile>Rekening</v-btn>
                    <v-card-text class="">
                        <p>BCA : 0584724782428</p>
                        <p>BRI : 03845724242</p>
                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
                </v-card>
            </v-container>
            </v-col>

            <v-col
            cols="12"
            md="8"
            >
            <v-container v-if="data">
                <v-card color="success"
                class="border-edit"
                tile

                >
                <v-card-text style="color:white !important">
                    Terimakasih anda telah melakukan pembayaran zakat {{data.jenis_zakat}},
                    kode pembayaran anda adalah <h3>{{data.kode}}</h3>
                    kami akan mengkonfirmasi pembayaran anda 1x24 jam. untuk mengecek status pembayaran
                    silakan klik <router-link to="/cek-pembayaran">disini</router-link>
                </v-card-text>

                </v-card>
            </v-container>
            <v-container v-if="!data">
                <v-card
                class="border-edit"
                tile
                >
                <v-btn small color="teal darken-2" class="white--text" tile>Bayar Zakat</v-btn>
                    <v-card-text class="">
                    <v-form
                    ref="form"
                    v-model="valid"
                    :lazy-validation="lazy"
                    >


                    <v-select
                        label="Jenis Zakat"

                        v-model="jenis_zakat"
                         :items="items"
                         item-text="description"
                         item-value="id"
                         :rules="namaRules"
                        outlined
                    ></v-select>


                    <v-text-field
                        label="Nama Lengkap"

                        v-model="nama"
                        :rules="namaRules"
                        outlined
                        color="teal darken-2"
                    ></v-text-field>


                    <v-text-field
                        label="No HP"
                        v-model="nohp"
                        :rules="nohpRules"

                        outlined
                        color="teal darken-2"
                    ></v-text-field>


                     <v-text-field
                        label="Email"
                        v-model="email"
                        :rules="emailRules"

                        outlined
                        color="teal darken-2"
                    ></v-text-field>


                    <v-text-field v-if="jenis_zakat == 38 || jenis_zakat == 44"
                        :label="jenis_zakat == 38 ? 'jumlah orang' : (jenis_zakat == 44 ? 'Beras yang dikeluarkan' : '')"

                        v-model="jumlah_orang"
                        :rules="orangRules"
                        outlined
                        color="teal darken-2"
                    ></v-text-field>

                    <!-- <v-text-field v-if=""
                        label="Beras yang dikeluarkan"
                        placeholder="Jumlah beras yang dikeluarkan"
                        v-model="jumlah_orang"
                        :rules="orangRules"
                        outlined
                        color="teal darken-2"
                    ></v-text-field> -->

                    <v-text-field
                        label="Jumlah Transfer"

                        outlined
                        color="teal darken-2"
                        placeholder="contoh : 10000000.00"
                        v-model="jumlah_transfer"
                        :rules="jumlahRules"
                        :disabled="jenis_zakat == 38 || jenis_zakat == 44"
                    ></v-text-field>
                    <label for="">Bukti Pembayaran</label>
                    <input type="file" @change="eventChange" accept="image/*">
                    <v-text-field
                        label="Jumlah Orang"

                        v-model="foto"
                        :rules="namaRules"
                        outlined
                        style="display:none"
                        color="teal darken-2"
                    ></v-text-field>

                    <v-row>
                            <v-col
                            cols="12"
                            align="right"

                            >
                              <v-btn
                              block
                              color="teal darken-2"
                              class="white--text"
                              :disabled="!valid"
                              :loading="loading"
                              @click="save()"
                                >
                                Bayar
                                </v-btn>
                            </v-col>
                        </v-row>
                     </v-form>
                    </v-card-text>

                    <v-card-actions class="">

                    </v-card-actions>
                </v-card>
            </v-container>

            </v-col>

        </v-row>
    </v-app>
</template>

<script>
export default {
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
            v => v > 0 || 'Format salah',
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

      computed:{
          transfer: {
                get(){
                    return this.jumlah_transfer
                },
                set(newValue){
                    let baru = newValue.splice()
                    console.log(baru)
                    this.jumlah_transfer = newValue

                }
          },

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

      methods:{
           formatAsCurrency (value,dec) {
            dec = dec || 0
                if (value === null) {
                    return null
                }
                return '' + value.toFixed(dec).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
            },

            eventChange(event){
                const files = event.target.files
                this.foto = files[0]
            },
            async save(){
                this.loading = true
                let url = '/pembayaran-zakat'
                let data  = new FormData()
                data.append('jenis_zakat',this.jenis_zakat)
                data.append('nama',this.nama)
                data.append('email',this.email)
                data.append('nohp',this.nohp)
                data.append('jumlah_transfer',this.jumlah_transfer)
                data.append('foto',this.foto)
                await this.axios.post(url,data)
                .then((ress) => {
                    console.log(ress)
                    this.data= ress.data.data
                })
                .catch((err)=>{
                    console.log(err)
                })
                this.loading = false
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

<style>

</style>
