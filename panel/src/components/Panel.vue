<template>
  <div>
    <div class="tile is-ancestor">
      <div class="tile is-parent is-8">
        <article class="tile is-child box col-md-7">
          <p class="title control" :class="{'is-loading': isloading}">
             Statistics Request in Bpm  <div v-show="error">{{ notRequest }}</div>
          </p>
          <chart :type="'line'" :data="stockData" :options="options" class="panel"></chart>
        </article>
      </div>
      <div class="tile is-parent is-4">
        <article class="tile is-child box">
          <div class="block">
            <!--<p class="title is-5">Params</p>-->
          </div>
          <div class="block">
            <div class="control is-horizontal">
              <div class="control-label">
                <label class="label">Symbol</label>
              </div>
              <!--<div class="control">-->
                <!--<div class="select is-fullwidth">-->
                  <!--<select v-model="params.symbol">-->
                    <!--<option v-for="s in symbols" :value="s">{{s}}</option>-->
                  <!--</select>-->
                <!--</div>-->
              <!--</div>-->
            </div>
            <!--<div class="control is-horizontal">-->
              <!--<div class="control-label">-->
                <!--<label class="label">Days</label>-->
              <!--</div>-->
              <!--<div class="control is-fullwidth">-->
                <!--<input class="input" min="0" max="720" type="number" v-model="params.numberOfDays">-->
              <!--</div>-->
            <!--</div>-->
            <!--<div class="control is-horizontal">-->
              <!--<div class="control-label">-->
                <!--<label class="label">Period</label>-->
              <!--</div>-->
              <!--<div class="control">-->
                <!--<div class="select is-fullwidth">-->
                  <!--<select v-model="params.dataPeriod">-->
                    <!--<option v-for="p in periods" :value="p">{{p}}</option>-->
                  <!--</select>-->
                <!--</div>-->
              <!--</div>-->
            <!--</div>-->
            <div class="control is-horizontal">
              <!--<div class="control-label">-->
                <!--<label class="label"></label>-->
              <!--</div>-->
              <div class="control">
                <button class="button is-primary" :class="{'is-loading': isloading}" @click="loadData">Get</button>
              </div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
    import Chart from 'vue-bulma-chartjs'
    const api = '/api/statistic-request'

    export default {
        components: {
            Chart
        },
        data () {
            return {
                error : false,
                notRequest:'(Unfortunately you have no requests ...)',
                params: {
                    symbol: 'AAPL',
                    numberOfDays: 450,
                    dataPeriod: 'Month'
                },
                symbols: ['AAPL', 'MSFT', 'JNJ', 'GOOG'],
                periods: ['Day', 'Week', 'Month', 'Quarter', 'Year'],
                data: [],
                labels: [],
                isloading: false,
                options: {
                    legend: { display: false },
                    animation: { duration: 0 },
                    scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'month'
                            }
                        }]
                    }
                }
            }
        },
        computed: {
            stockData () {
                return {
                    labels: this.labels,
                    datasets: [{
                        fill: false,
                        lineTension: 0.25,
                        data: this.data,
                        label: 'Duration',
                        pointBackgroundColor: '#1fc8db',
                        pointBorderWidth: 1
                    }]
                }
            }
        },
        methods: {
            loadData () {
                this.isloading = true
                this.labels.length = 0
                this.data.length = 0

                this.$http({
                    url: api,
                }).then((response) => {
                    let dates = JSON.parse(response.data).date
                    let price = JSON.parse(response.data).durations
                    this.data.push(...price)
                    this.labels.push(...dates)
                    this.isloading = false
                    this.error = false
                }).catch((error) => {
                    this.error = true
                })
            }
        }
    }
</script>

<style scoped>
</style>