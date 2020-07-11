<template>
    <v-app>
        <Progress v-if="loading"/>
        <v-container v-if="!loading">
            <v-btn small color="teal darken-2" class="white--text" tile>Data Penyalur Zakat</v-btn>
            <v-card
            class="border-edit"
            tile
            >
                <v-card-text class="text-center">
                    <v-container>
                        <v-row justify="center" align="center">
                            <v-col
                                cols="6"
                            >
                            <v-text-field
                                v-model="keyword"
                                label="Pencarian"
                                v-on:keyup = "go"
                                color="teal darken-2"
                            ></v-text-field>
                            </v-col>

                            <v-col
                                cols="6"
                                align="right"
                            >
                                <v-btn color="primary"  :to="urlcreate" small tile v-if="user.id_role == 23 || user.id_role == 35">
                                    Tambah Data
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>

                    <v-simple-table>
                        <template v-slot:default>
                        <thead>
                            <tr>
                            <th class="text-left">Nama Lembaga</th>
                            <th class="text-left">Alamat</th>
                            <th class="text-left">Email</th>
                            <th class="text-left">No HP</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in data" :key="item.id">
                                <td class="text-left">{{item.nama}}</td>
                                <td class="text-left">{{item.alamat}}</td>
                                <td class="text-left">
                                    {{item.user ? item.user.email : 'Belum diapproval'}}
                                </td>
                                <td class="text-left">{{item.nohp}}</td>
                                <td class="text-left">
                                    <v-btn x-small color="secondary" dark v-if="item.status == 0">Belum DiApproval</v-btn>
                                    <v-btn x-small color="success" dark v-else-if="item.status == 1">Aktive</v-btn>
                                    <v-btn x-small color="primary" dark v-else-if="item.status == 3">Non-Aktif</v-btn>
                                    <v-btn x-small color="red" dark v-else-if="item.status == 2">Ditolak</v-btn>
                                </td>

                                <td class="text-left">
                                <v-btn color="success" v-on:click="edit(item.id)" fab x-small dark v-if="user.id_role == 23 || user.id_role == 35">
                                    <v-icon>mdi-circle-edit-outline</v-icon>
                                </v-btn>
                                <v-btn color="error" fab x-small @click="dialogDelete(item.id)" v-if="(item.status == 0 || item.status == 2) && (user.id_role == 23 || user.id_role == 35)" >
                                    <v-icon>mdi-delete-outline</v-icon>
                                </v-btn>
                                <v-btn x-small color="success" dark v-if="(user.id_role == 34 || user.id_role == 23) && item.status == 0" @click="openDialog(item.id)">Konfirmasi</v-btn>
                                 <v-btn x-small color="primary" dark v-if="(user.id_role == 34 || user.id_role == 23) && (item.status == 3)" @click="confirmasi(1,item.id)">Aktifkan</v-btn>
                                 <v-btn x-small color="secondary" dark v-if="(user.id_role == 34 || user.id_role == 23) && (item.status == 1)" @click="confirmasi(3,item.id)">Non Aktifkan</v-btn>
                                </td>
                            </tr>

                        </tbody>
                        </template>
                    </v-simple-table>
                </v-card-text>
                <div class="text-center">
                    <v-pagination
                    v-model="page"
                    :length="lengthpage"
                    :total-visible="7"
                    @input="go"
                    color="teal darken-2"
                    ></v-pagination>
                </div>
                <v-card-actions class="">

                </v-card-actions>
            </v-card>

            <v-dialog
            v-model="dialog"
            max-width="340"
            >
            <v-card>
                <v-card-title class="headline">Apa anda yakin menghapus ?</v-card-title>

                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn

                    text
                    @click="dialog = false"
                >
                    Cancel
                </v-btn>

                <v-btn
                    color="red"
                    text
                    @click="deleteData()"
                >
                    Delete
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>

             <v-dialog
            v-model="dialog_konfirm"
            max-width="450"
            >
            <v-card>
                <v-card-title class="headline">Konfirmasi Penyalur ?</v-card-title>

                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                    color="red"
                    text
                    @click="confirmasi(2)"
                >
                    Tolak
                </v-btn>

                <v-btn
                    color="success"
                    text
                    @click="confirmasi(1)"
                >

                    Terima
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-container>
    </v-app>

</template>
<script>

import CrudMixin from '../../mixins/CrudMixin'
export default {
    name: 'pnyalur',
    data() {
        return {
            dialog_konfirm:false,
            id_penyalur:'',
        }
    },
    mixins:[CrudMixin],
    methods: {
        openDialog(id) {
            this.dialog_konfirm = !this.dialog_konfirm
            this.id_penyalur = id
        },
        async confirmasi(status,id = null) {
            let id_penyalur = id ? id : this.id_penyalur
            let url = this.$route.path + '/' + id_penyalur
             let data = new FormData()
            data.append('status',status)

            data.append('_method','PUT')

            await this.axios.post(url,data,this.config)
            .then((ress) => {
                console.log(ress)
                this.setSnakbar({
                    status:true,
                    pesan:ress.data.message,
                    color:'success'
                })
                this.go()
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
            this.dialog_konfirm = false
        }
    }
}
</script>

