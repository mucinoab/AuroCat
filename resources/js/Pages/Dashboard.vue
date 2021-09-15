<template>
    <app-layout title="Dashboard">
    <Head title="Dashboard" />

        <div style="background-color: #293042;" class="principal">
            <header>
                <h1>Dashboard</h1>
                <p id="last-update" style="align-self: flex-end; color: lightgray"></p>
            </header>

            <div class="graph-container">
                <div class="side"></div>
                    <div class="graph" id="chart-0">
                        <div class="loading">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                            <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                            <animateTransform attributeType="xml"
                                attributeName="transform"
                                type="rotate"
                                from="0 25 25"
                                to="360 25 25"
                                dur="1s"
                                repeatCount="indefinite"/>
                            </path>
                            </svg>
                        </div>
                    </div>

                    <div class="graph" id="chart-1">
                        <div class="loading">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                                    <animateTransform attributeType="xml"
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 25 25"
                                    to="360 25 25"
                                    dur="1s"
                                    repeatCount="indefinite"/>
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="side"></div>
                    <div class="side"></div>
                    <div class="graph" id="chart-2">
                        <div class="loading">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                                    <animateTransform attributeType="xml"
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 25 25"
                                    to="360 25 25"
                                    dur="1s"
                                    repeatCount="indefinite"/>
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="graph" id="chart-3">
                        <div class="loading">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                                    <animateTransform attributeType="xml"
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 25 25"
                                    to="360 25 25"
                                    dur="1s"
                                    repeatCount="indefinite"/>
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="side"></div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>
.principal{
  scroll-behavior: smooth;
  font-family: sans-serif;
}

.principal{
  margin: 0;
}

header {
  color: white;
  display: flex;
  justify-content: space-between;
  flex-direction: row;
  padding: 0em 1em 2em 4em;
}
p {
  color: #EEE;
  margin: 10px;
}

.graph-container {
  align-items: center;
  color: white;
  display: grid;
  grid-template-columns: 0.5fr repeat(2, 2fr) 0.5fr;
  grid-template-rows: repeat(2, 1fr);
  grid-auto-flow: row;
  padding-bottom: 5em;
  grid-template-areas:
    "side graph graph side"
    "side graph graph side"
}

.graph {
  margin: 0.5em;
  height: 40em;
}

.loading {
  height: 20em;
  width: 20em;
  margin: 0 auto 1em;
}

svg path, svg rect{
  fill: #85a2b6;
}
</style>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'

    export default {
        components: {
            AppLayout
        },
    }
</script>
