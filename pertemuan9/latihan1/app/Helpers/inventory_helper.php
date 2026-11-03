<?php

function is_admin(): bool
{
    return session()->get('role') === 'admin';
}

function rupiah($value): string
{
    return 'Rp ' . number_format((float) $value, 2, ',', '.');
}
