<template>
    <v-app>
        <v-container >
            <v-btn small color="teal darken-2" class="white--text" tile>Cetak Laporan Data Pembagian Zakat</v-btn>
            <v-card
            class="border-edit"
            tile
            >
                <v-card-text class="text-center">
                    <v-container>
                    <v-select
                        label="Periode"
                        :items="items"
                        v-model="tahun"
                        item-text="text"
                        item-value="value"
                        outlined
                    ></v-select>
                    <v-row>
                            <v-col
                            cols="12"
                            align="right"

                            >
                              <v-btn
                              block
                              color="primary"
                              class="white--text"
                              @click="cetak()"
                                >
                                Cetak
                                </v-btn>
                            </v-col>
                    </v-row>

                    </v-container>
                </v-card-text>

                <v-card-actions class="">

                </v-card-actions>
            </v-card>

        </v-container>
    </v-app>

</template>
<script>

export default {
    data: () => ({
        items:[],
        tahun:''
      }),
     async created(){
          let url = '/tahun-laporan'
          await this.axios.get(url)
          .then((ress) => {
              console.log(ress)

                this.items = ress.data.tahun_pembagian
          })
          .catch((err) => {
              console.log(err.response)
          })
      },
      methods:{
          cetak(){
              let url = 'http://' + window.location.host + '/laporan-pembagian?tahun=' + this.tahun
              window.open(url)
          }
      }
}
</script>

