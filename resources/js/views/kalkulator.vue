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
                <v-btn small color="teal darken-2" class="white--text" tile>Rincian</v-btn>
                    <v-card-text class="">
                        <p>Harga Emas : Rp {{harga_emas}}/gram</p>
                        <p>Harga Perak : Rp {{harga_perak}}/gram</p>
                        <p>Harga Beras : Rp {{harga_beras}}/Kg</p>
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
            <v-container>
                <v-card
                class="border-edit"
                tile
                v-if="total"
                >
                <v-card-text class="text-center">
                    <div v-if="total != 'blm'">
                        Zakat yang anda harus keluarkan
                        <h1 color="success">Rp {{jenis_zakat == 44 ? total * harga_beras : total}}</h1>
                    </div>
                    <div v-else>
                        <h2>Anda belum memasuki nisab</h2>
                    </div>
                </v-card-text>

                </v-card>
                <br v-if="total">
                <v-card
                class="border-edit"
                tile
                >
                <v-btn small color="teal darken-2" class="white--text" tile>Kalkulator Zakat</v-btn>
                    <v-card-text class="text-center">
                    <v-select
                        label="Pilihan Zakat"
                        :items="items"
                        v-model="jenis_zakat"
                        item-text="description"
                        item-value="id"
                        outlined
                    ></v-select>
                    <v-text-field
                        label="Jumlah Orang"

                        outlined
                        color="teal darken-2"
                        v-model="orang"
                        v-if="jenis_zakat == 38"
                    ></v-text-field>

                    <v-text-field
                        :label="jenis_zakat == 39 ? 'Simpanan Emas' : 'Simpanan Perak'"
                        placeholder="satuan gr"
                        outlined
                        color="teal darken-2"
                        v-model="emas_perak"
                        v-if="jenis_zakat == 39 || jenis_zakat == 40"
                    ></v-text-field>

                     <v-text-field
                        label="Modal usaha"
                        outlined
                        v-if="jenis_zakat == 41"
                        v-model="modal"
                        color="teal darken-2"
                    ></v-text-field>
                     <v-text-field
                        label="keuntungan"
                        outlined
                        v-if="jenis_zakat == 41"
                        v-model="keuntungan"
                        color="teal darken-2"
                    ></v-text-field>

                    <v-text-field
                        label="Piutang"
                        outlined
                        v-if="jenis_zakat == 41"
                        v-model="piutang"
                        color="teal darken-2"
                    ></v-text-field>
                    <v-text-field
                        label="Hutang"
                        outlined
                        v-if="jenis_zakat == 41"
                        v-model="hutang"
                        color="teal darken-2"

                    ></v-text-field>
                    <v-text-field
                        label="Kerugian"
                        outlined
                        v-if="jenis_zakat == 41"
                        v-model="kerugian"
                        color="teal darken-2"
                    ></v-text-field>

                    <v-text-field
                        label="Penghasilan perbulan"
                        outlined
                        v-if="jenis_zakat == 42"
                        v-model="penghasilan"
                        color="teal darken-2"
                    ></v-text-field>

                    <v-text-field
                        label="Kebutuhan perbulan"
                        outlined
                        v-if="jenis_zakat == 42"
                        v-model="kebutuhan"
                        color="teal darken-2"
                    ></v-text-field>



                     <v-text-field
                        label="Total tabungan"
                        outlined
                        v-if="jenis_zakat == 43"
                        v-model="tabungan"
                        color="teal darken-2"
                    ></v-text-field>
                    <v-text-field
                        label="Hasil Pertanian"
                        outlined
                        v-if="jenis_zakat == 44"
                        v-model="hasil_panen"
                        color="teal darken-2"
                    ></v-text-field>

                    <v-select
                        label="Jenis Perairan"
                        :items="['Buatan','Air Hujan/Sungai']"
                        v-model="perairan"
                        v-if="jenis_zakat == 44"
                        outlined
                    ></v-select>

                    <v-row>
                            <v-col
                            cols="12"
                            align="right"

                            >
                              <v-btn
                              block
                              color="teal darken-2"
                              class="white--text"
                              @click="hitung()"
                                >
                                Hitung
                                </v-btn>
                            </v-col>
                    </v-row>

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
        items:[],
        harga_beras:'',
        harga_emas: '',
        harga_perak: '',
        jenis_zakat:'',
        orang:'',
        emas_perak:'',
        modal:'',
        keuntungan:'',
        piutang:'',
        hutang:'',
        kerugian:'',
        penghasilan:'',
        kebutuhan:'',
        tabungan:'',
        hasil_panen:'',
        perairan:'',
        total:'',
        nisob_pertanian: 612,
        nisob_emas:'',
        nisob_perak:'',
      }),
      methods:{
          hitung(){
              if (this.jenis_zakat == 38) {
                  this.total = this.orang * (3 * this.harga_beras)
              } else if(this.jenis_zakat == 39 || this.jenis_zakat == 40) {
                  if (this.jenis_zakat == 39) {
                      this.total = this.emas_perak * this.harga_emas
                        this.hitung_emas()
                  } else {
                      this.total = this.emas_perak * this.harga_perak
                      this.hitung_perak()

                  }
              } else if(this.jenis_zakat == 41) {
                  this.total = (modal + keuntungan + piutang) - (hutang + kerugian)
                  this.hitung_emas()
              } else if(this.jenis_zakat == 42) {
                  this.total = (penghasilan - kebutuhan) * 12
                 this.hitung_emas()
              } else if (this.jenis_zakat == 43) {
                  this.total = this.tabungan
                  this.hitung_emas()
              } else if (this.jenis_zakat == 44){
                  this.total = this.hasil_panen
                  this.hitung_petanian()
              }

          },

          hitung_emas(){
               if (this.total >= this.nisob_emas) {
                          this.total = this.total * (2.5/100)
                      } else {
                          this.total = 'blm'
                }
          },
          hitung_perak() {
              if (this.total >= this.nisob_perak) {
                          this.total = this.total * (2.5/100)
                      } else {
                          this.total = 'blm'
                }
          },
          hitung_petanian(){
               if (this.total >= this.nisob_pertanian) {

                          if (this.perairan == 'Buatan') {
                            this.total = this.total * (5/100)
                          } else if(this.perairan == 'Air Hujan/Sungai') {
                            this.total = this.total * (10/100)
                          } else{
                              this.total = 'blm'
                          }
                    } else {
                          this.total = 'blm'
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
              this.harga_emas = ress.data.harga.harga_emas
              this.harga_perak = ress.data.harga.harga_perak
              this.nisob_emas = 85 * ress.data.harga.harga_emas
              this.nisob_perak = 595 *  ress.data.harga.harga_perak

          })
          .catch((err) => {
              console.log(err.response)
          })
      }
}
</script>

<style>

</style>
