<template>
    <svg class="graph" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
         viewBox="0 0 170 170">
        <def>
        </def>
        <!--<pattern id="p1" x="-85" y="-85" patternUnits="userSpaceOnUse" width="100%" height="100%">-->
            <!--<rect x="0" y="0" width="170" height="170" fill="green"></rect>-->
            <!--<image xlink:href="https://pubgbattles.ru/images/font-fill.jpg" width="450"/>-->
        <!--</pattern>-->
        <g transform="translate(85 85)">
            <circle cx="0" cy="0" r="75" stroke-width="16" stroke="#553438" fill="none">
            </circle>
            <path fill="none" stroke-width="16"
                  stroke="#edbb29" stroke-linecap="round"
                  :d="describeArcStroke(0,0,75,0,359 - 360*value/max)"
            >
            </path>
            <text text-anchor="middle" alignment-baseline="middle"
                  font-size="70" fill="#ffffff" y="-20"
            >
                {{value}}
            </text>
            <text text-anchor="middle" alignment-baseline="middle"
                  font-size="35" fill="#ffffff"
                  x="0" y="20"
            >
                {{text}}
            </text>
        </g>
    </svg>
</template>

<script>
    export default {
        name: "CircleGraph",
        props: {
            min: {
                default: 0
            },
            max: {
                default: 100
            },
            value: {
                default: 50
            },
            text: {
                default: 'percent'
            }
        },
        mounted() {
            // this.$moment.locale('ru');
            // console.log(this.$moment.locales());
            // console.log(this.$moment().from(this.$moment().subtract(1, 'days'), true));
        },
        methods: {
            polarToCartesian(centerX, centerY, radius, angleInDegrees) {
                var angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;

                return {
                    x: centerX + (radius * Math.cos(angleInRadians)),
                    y: centerY + (radius * Math.sin(angleInRadians))
                };
            },

            getArcData(x, y, radius, startAngle, endAngle, changeSweep = null, changeLarge = null) {
                let center = this.polarToCartesian(x, y, 0, 0 + (startAngle + endAngle) / 2);
                let start = this.polarToCartesian(x, y, radius, 0 + endAngle);
                let end = this.polarToCartesian(x, y, radius, 0 + startAngle);
                let sweep = (endAngle - startAngle <= 180) ? 0 : 1;
                let gRad = Math.sqrt(
                    (start.x - end.x) * (start.x - end.x) +
                    (start.y - end.y) * (start.y - end.y)
                );

                if (changeSweep != null) {
                    sweep = changeSweep;
                }

                return {
                    center, start, end, sweep, gRad: gRad, radius, changeLarge
                }
            },
            describeArc(x, y, radius, startAngle, endAngle) {
                let arc = (typeof x == 'object')
                    ? x
                    : this.getArcData(x, y, radius, startAngle, endAngle);

                // console.log(arc);

                let d = [
                    "M", arc.center.x, arc.center.y,
                    "L", arc.start.x, arc.start.y,
                    "A", arc.radius, arc.radius, 0, arc.sweep, 0, arc.end.x, arc.end.y,
                    "Z"
                ].join(" ");

                return d;
            },

            describeArcStroke(x, y, radius, startAngle, endAngle) {
                let arc = (typeof x == 'object')
                    ? x
                    : this.getArcData(x, y, radius, startAngle, endAngle);

                // console.log(arc);

                let large = 0;
                if (arc.changeLarge != null) {
                    large = arc.changeLarge;
                }

                let d = [
                    "M", arc.start.x, arc.start.y,
                    "A", arc.radius, arc.radius, 0, arc.sweep, large, arc.end.x, arc.end.y,
                ].join(" ");

                return d;
            }
        }
    }
</script>

<style scoped>

</style>