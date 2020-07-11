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
                        <p>Harga Emas : 950.000/gram</p>
                        <p>Harga Perak : 560.000/gram</p>
                        <p>Harga Beras : 11.000/Kg</p>
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
                >
                <v-btn small color="teal darken-2" class="white--text" tile>Kalkulator Zakat</v-btn>
                    <v-card-text class="text-center">
                    <v-select
                        label="Pilihan Zakat"
                        outlined
                    ></v-select>
                    <v-text-field
                        label="Total Tabungan"
                        single-line
                        outlined
                        color="teal darken-2"
                    ></v-text-field>

                    <v-text-field
                        label="Total Emas"
                        single-line
                        outlined
                        color="teal darken-2"
                    ></v-text-field>

                     <v-text-field
                        label="Total Perak"
                        single-line
                        outlined
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

</script>

<style>

</style>
