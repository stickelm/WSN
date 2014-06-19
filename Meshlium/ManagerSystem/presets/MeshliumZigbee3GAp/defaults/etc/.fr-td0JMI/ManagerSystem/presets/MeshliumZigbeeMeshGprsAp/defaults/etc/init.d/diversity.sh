#!/bin/bash

sysctl -w dev.wifi0.diversity=0
sysctl -w dev.wifi0.txantenna=0
sysctl -w dev.wifi0.rxantenna=0

sysctl -w dev.wifi1.diversity=0
sysctl -w dev.wifi1.txantenna=0
sysctl -w dev.wifi1.rxantenna=0