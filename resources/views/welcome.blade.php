<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1,minimal-ui" name="viewport">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Material+Icons">
    <link rel="stylesheet" href="https://unpkg.com/vue-material@beta/dist/vue-material.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-material@beta/dist/theme/default.css">
    <style>
        .theme-red .vdatetime-popup__header,
        .theme-red .vdatetime-calendar__month__day--selected > span > span,
        .theme-red .vdatetime-calendar__month__day--selected:hover > span > span {
            background: #ff5252;
            color: white;
        }
        .theme-red .vdatetime-year-picker__item--selected,
        .theme-red .vdatetime-time-picker__item--selected,
        .theme-red .vdatetime-popup__actions__button {
            color: #ff5252;
        }
        .md-field .md-input, .md-field .md-textarea {
            width: 300px;
            height: 32px;
            padding: 0;
            display: block;
            -webkit-box-flex: 1;
            flex: 1;
            border: none;
            background: none;
            transition: .4s cubic-bezier(.25, .8, .25, 1);
            transition-property: font-size, padding-top, color;
            font-family: inherit;
            font-size: 20px;
            line-height: 32px;
        }
        .md-accent {
            background-color: #a90f0e !important;
        }

        .md-list.md-theme-default {

            background-color: #fff0;
            color: rgba(0, 0, 0, 0.87);
            color: var(--md-theme-default-text-primary-on-background, rgba(0, 0, 0, 0.87));
        }
        .red {
            background-color: rgba(243, 61, 50, 0.75)
        }
        .green {
            cursor: pointer;
            background-color: #b9ceb954
        }
        .green:hover {
            background-color: rgba(118, 134, 118, 0.33)
        }
    </style>
</head>
<body style="background-size: cover;
    background-image: linear-gradient(rgba(0, 0, 0, 0.16), rgb(105, 0, 0)), url(background.jpg) !important;
    background-repeat: no-repeat;">
<div id="app">
    <md-toolbar class="md-accent">
        <h3 class="md-title" style="flex: 1">Schedule of the dance of The Death</h3>
    </md-toolbar>
    <div>
        <div class="md-title" style="color: white">Select a day</div>
        <div class="md-field md-theme-default" style="width: 300px;color: white">
            <datetime
                    ref="datepicker"
                    id="datepicker"
                    v-model="datetimeEmpty"
                    input-class="md-input"
                    value-zone="America/Bogota"
                    zone="America/Bogota"
                    :phrases="{ok: 'Continue', cancel: 'Exit'}"
                    :week-start="7"
                    auto
                    class="theme-red"
            ></datetime>
        </div>
    </div>
    <md-dialog :md-active.sync="showDialogCreateSchedule">
        <md-dialog-title>Create Schedule</md-dialog-title>
        <md-dialog-content>
            <md-field>
                <label>Name of dancer</label>
                <md-input v-model="formName"></md-input>
            </md-field>
            <md-field>
                <label>Email of dancer</label>
                <md-input type="email" v-model="formEmail"></md-input>
            </md-field>
        </md-dialog-content>
        <md-dialog-actions>
            <md-button class="md-primary" @click="showDialogCreateSchedule = false">Close</md-button>
            <md-button class="md-primary" @click="saveSchedule">Save</md-button>
        </md-dialog-actions>
    </md-dialog>
    <md-list>
        <md-list-item :key="i" v-for="(hour, i) in hours" @click="openToCreate(i)"
                      :class="{green: hour === null, red:hour !== null}">
            <span v-if="hour === null">@{{ i }}:00  Available</span>
            <span v-else>
                <div class="md-list-item-text">
          <span>@{{ i }}:00  Busy</span>
          <span>@{{ hour.name }}  (@{{ hour.email }})</span>
        </div>
            </span>
        </md-list-item>
    </md-list>
    <md-snackbar md-position="center" :md-duration="duration" :md-active.sync="showSnackbar" md-persistent>
        <span>@{{ snackMessage }}</span>
        <md-button class="md-primary" @click="showSnackbar = false">Close</md-button>
    </md-snackbar>
</div>
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/vue-material@beta"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-datetime@1.0.0-beta.10/dist/vue-datetime.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luxon@1.12.1/build/global/luxon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-datetime@1.0.0-beta.10/dist/vue-datetime.min.js"></script>
<script>
    Vue.use(VueMaterial.default);

    new Vue({
        el: '#app',
        data: {
            showSnackbar: false,
            showDialogCreateSchedule: false,
            snackMessage: '',
            datetimeEmpty: moment().format(),
            hours: [],
            current_hour: null,
            formName: '',
            formEmail: '',
            duration: 3000
        },
        methods: {
            getByDay: function () {
                that = this;
                axios.get('/api/1.0/events/getByDay/' + this.datetimeEmpty)
                    .then(function (response) {
                        that.hours = response.data.data;
                    })
            },
            openToCreate: function (i) {
                that = this;
                if (that.hours[i] === null) {
                    that.current_hour = i;
                    that.showDialogCreateSchedule = true;
                }
            },
            saveSchedule: function () {
                that = this;
                let date = moment(that.datetimeEmpty);
                date.set({
                    hour: that.current_hour,
                    minute: 0,
                    second: 0
                });
                $data = {
                    name: that.formName,
                    email: that.formEmail,
                    date: date.format()
                };

                console.log($data);
                axios.post('/api/1.0/events', $data)
                    .then(function (response) {
                        if(response.data.status === 400){
                            that.snackMessage = 'Please fill in all the fields';
                            that.showSnackbar = true;
                        }else{
                            that.getByDay();
                            that.snackMessage = 'Scheduled Succesfully';
                            that.showSnackbar = true;
                            that.showDialogCreateSchedule = false;
                            that.formName = '';
                            that.formEmail = '';
                        }
                    }).catch(function (response) {
                    that.snackMessage = 'this time is busy, choose another.';
                    that.showSnackbar = true;
                })
            }
        },
        watch: {
            datetimeEmpty: function (value) {
                that = this;
                now = moment().subtract(1, 'day');
                var past = now.isAfter(value);
                console.log(moment(value).isoWeekday());
                if (past) {
                    that.snackMessage = 'Sorry its a past day, Pick another day';
                    that.showSnackbar = true;
                    that.hours = [];
                } else if (moment(value).isoWeekday() === 6 || moment(value).isoWeekday() === 7) {
                    that.snackMessage = 'Sorry, the death only dances from Monday to Friday';
                    that.showSnackbar = true;
                    that.hours = [];
                } else {
                    axios.get('/api/1.0/events/getByDay/' + value)
                        .then(function (response) {
                            that.hours = response.data.data;
                        })
                }
            }
        },
        mounted() {
            this.getByDay();
        }
    })
</script>
</body>
</html>
