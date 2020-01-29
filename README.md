# Rfpl-Laravel
A Response First Process Later Package for Laravel

#Usage

`$rfpl = new RfplLaravel\CacheService;
try {
    $rfpl->handle();
} catch (\Exception $th) {
    //throw $th;
}

# To ignore Routes jus pass an array of routes pattern

$rfpl = new RfplLaravel\CacheService([
  'test',
  'abx\*',
  '*\abc',
  'xyz\lmn'
]);
try {
    $rfpl->handle();
} catch (\Exception $th) {
    //throw $th;
}
`
