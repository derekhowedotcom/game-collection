<template>
  <div class="flex items-center justify-center mt-10">
    <div v-if="isLoading"
        class="inline-block content-center animate-spin w-20 h-20 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
    <div v-show="!isLoading" id="videoWindow" class="video"></div>
  </div>
</template>

<script setup>
import {ref, onMounted, onUnmounted, watch} from 'vue';
import Quagga from 'quagga';

const scannerActive = ref(false);
const foundCodes = ref(null);
const isLoading = ref(true);

const props = defineProps({
  startBarcodeScanner: {
    type: Boolean,
    required: true,
  },
});

watch(() => props.startBarcodeScanner, (newValue) => {
  if (newValue) {
    // Start the scanner
    start();
    console.log('Barcode scanner started');
  } else {
    // Stop the scanner
    stop();
    console.log('Barcode scanner stopped');
  }
});

// Emit the found barcode
const emit = defineEmits(['barcodeFound']);

const start = () => {
  isLoading.value = true;
  scannerActive.value = true;
  const config = {
    locate: true,
    inputStream: {
      name: 'live',
      type: 'LiveStream',
      target: document.querySelector('#videoWindow'),
    },
    decoder: {
      readers: ['ean_reader'],
      multiple: true,
    },
    locator: {
      halfSample: true,
      patchSize: 'medium',
    },
    debug: {
      drawBoundingBox: true,
      showFrequency: true,
      drawScanline: true,
      showPattern: true
    }
  };
  Quagga.init(config, (err) => {
    if (err) {
      console.log(err);
      return;
    }
    console.log('initialization complete');
    Quagga.start();
    isLoading.value = false;
  });
};

const stop = () => {
  scannerActive.value = false;
  Quagga.stop();
};

onMounted(() => {
  Quagga.onDetected((data) => {
    const foundResult = data[0];
    const foundCode = {
      code: foundResult.codeResult.code,
      type: foundResult.codeResult.format,
    };
    console.log(foundCode);
    foundCodes.value = foundCode;
    emit('barcodeFound', foundCode);
    stop();
  });
});

onUnmounted(() => {
  Quagga.offDetected();
});

</script>

<style>
.video {
  width: 100%;
}
canvas {
  display: none;
}
</style>
