<div class="col-sm-6 col-md-6">
    <label for="bank_name" class="form-label required">Chọn ngân hàng:</label>

    <input
        type="text"
        id="bank_name"
        name="bank_name"
        placeholder="Nhập tên ngân hàng"
        class="form-control @error('bank_name') is-invalid @enderror"
        autocomplete="off"
        oninput="handleBankSearch(this.value)"
        value="{{ $search }}"
    />

    <!-- Hiển thị danh sách ngân hàng nếu có kết quả -->
    @if(!empty($banks))
        <ul class="list-group mt-2" style="position: absolute; z-index: 1000; width: 100%;">
            @foreach($banks as $bank)
                <li
                    wire:click="selectBank('{{ $bank }}')"
                    class="list-group-item list-group-item-action"
                    style="cursor: pointer;"
                >
                    {{ $bank }}
                </li>
            @endforeach
        </ul>
    @endif

    @error('bank_name')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<!-- Thêm đoạn JavaScript -->
<script>
    function handleBankSearch(value) {
        console.log("Người dùng nhập: ", value); // Log giá trị nhập để kiểm tra
        Livewire.emit('searchUpdated', value); // Gửi sự kiện đến Livewire với giá trị tìm kiếm
    }
</script>
