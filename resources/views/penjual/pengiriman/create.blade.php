<form action="{{ route('penjual.pengiriman.store') }}" method="POST">
    @csrf
    <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">

    <label>Pilih Kurir</label>
    <select name="kurir" required>
        <option value="">-- Pilih --</option>
        <option value="jne">JNE</option>
        <option value="jnt">J&T</option>
        <option value="parcel">Parcel</option>
        <option value="ninja express">Ninja Express</option>
    </select>

    <button type="submit">Kirim ke Kurir</button>
</form>
