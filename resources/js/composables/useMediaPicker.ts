import { Ref, ref } from 'vue';

export function useMediaPicker(
  emit: (event: string, payload: string) => void,
  emitEvent: string
) {
  const fileInput: Ref<HTMLInputElement | null> = ref(null);

  function handleCardClick() {
    fileInput.value?.click();
  }

  function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
      const url = URL.createObjectURL(file);
      emit(emitEvent, url);
    }
  }

  return {
    fileInput,
    handleCardClick,
    handleFileChange,
  };
}
