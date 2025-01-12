<?php
// India Map SVG with country outline
?>
<svg id="indiaMap" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 600" 
    style="border: 2px solid #dee2e6; background: #ffffff; min-height: 600px; width: 100%;">
    
    <!-- India Outline -->
    <path id="indiaOutline" 
        d="M120,180 L140,160 L180,140 L220,130 L260,140 L300,160 L340,180 
        L360,220 L350,260 L360,300 L350,340 L340,380 L320,420 L300,460 
        L280,500 L240,520 L200,540 L160,520 L140,480 L120,440 L110,400 
        L100,360 L110,320 L120,280 L110,240 L120,180 Z" 
        class="country-outline"/>

    <!-- Major States -->
    <path id="Rajasthan" 
        d="M120,240 L160,220 L200,230 L220,260 L200,290 L160,280 L140,260 Z" 
        class="state-path" title="Rajasthan"/>
        
    <path id="Gujarat" 
        d="M110,320 L140,300 L170,310 L180,340 L160,360 L130,350 L110,320 Z" 
        class="state-path" title="Gujarat"/>
        
    <path id="Maharashtra" 
        d="M160,360 L200,350 L240,360 L260,390 L240,420 L200,410 L170,390 Z" 
        class="state-path" title="Maharashtra"/>
        
    <path id="Karnataka" 
        d="M170,390 L200,410 L240,420 L250,450 L230,480 L190,470 L170,450 Z" 
        class="state-path" title="Karnataka"/>
        
    <path id="TamilNadu" 
        d="M190,470 L230,480 L250,510 L240,540 L210,550 L190,530 L180,500 Z" 
        class="state-path" title="Tamil Nadu"/>
        
    <path id="Kerala" 
        d="M170,450 L190,470 L180,500 L160,520 L150,500 L160,480 Z" 
        class="state-path" title="Kerala"/>

    <!-- State Labels -->
    <text x="180" y="260" class="state-label">RJ</text>
    <text x="150" y="330" class="state-label">GJ</text>
    <text x="210" y="390" class="state-label">MH</text>
    <text x="210" y="440" class="state-label">KA</text>
    <text x="220" y="510" class="state-label">TN</text>
    <text x="170" y="490" class="state-label">KL</text>

    <style>
        .country-outline {
            fill: none;
            stroke: #343a40;
            stroke-width: 2;
            vector-effect: non-scaling-stroke;
        }
        .state-path {
            fill: #e9ecef;
            stroke: #495057;
            stroke-width: 1;
            transition: all 0.3s;
        }
        .state-path:hover {
            fill: #dee2e6;
            stroke: #212529;
            stroke-width: 2;
            cursor: pointer;
        }
        .state-label {
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: bold;
            fill: #212529;
            text-anchor: middle;
            dominant-baseline: middle;
            pointer-events: none;
        }
    </style>
</svg>
