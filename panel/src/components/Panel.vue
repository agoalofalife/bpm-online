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

              <div class="control">
                <div class="select is-fullwidth col-md-4">
                  <select class="form-control">
                    <option  v-for="date in dateFilter">{{ date }}</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="control is-horizontal">
              <div class="control">
                <button type="submit" class="btn btn-primary" :class="{'is-loading': isloading}" @click="loadData">Get</button>
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
                dateFilter : [],
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
            },
        },
        methods: {
            loadData () {
                this.isloading      = true
                this.labels.length = 0
                this.data.length   = 0

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

                  this.$http({
                    url: '/api/listDates',
                }).then((response) => {
                    this.dateFilter = JSON.parse(response.data).date
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