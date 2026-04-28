# Ages Grid

Znovupoužitelná tabulková komponenta pro [Nette](https://nette.org/) s [Nextras ORM](https://nextras.org/orm/). Podporuje řazení, filtrování, stránkování, akce na řádcích a export do Excelu.

**Požadavky:** PHP 8.4, Nette Application 3.1+, Nextras ORM 5.0+

---

## Instalace

```bash
composer require ages/grid
```

---

## Základní použití

Komponentu registrujte v Presenteru nebo Facade přes factory metodu:

```php
use Ages\Grid\Grid;
use Nextras\Orm\Collection\ICollection;

protected function createComponentGrid(): Grid
{
    $grid = new Grid($this->repository->findAll());

    $grid->addColumnString('name', 'Jméno')->sortAndFilter();
    $grid->addColumnDate('createdAt', 'Vytvořeno');
    $grid->addColumnNumber('price', 'Cena', unit: 'Kč');

    $grid->addAction(ActionType::Edit, 'edit', 'Upravit');
    $grid->addAction(ActionType::Delete, 'delete!', 'Smazat');

    return $grid;
}
```

V šabloně:

```latte
{control grid}
```

---

## Konstruktor

```php
new Grid(
    ICollection|OneHasMany $rawData,
    ?GridStyleInterface    $gridStyle = null
)
```

| Parametr | Popis |
|---|---|
| `$rawData` | Kolekce Nextras ORM nebo relace `OneHasMany` |
| `$gridStyle` | Vlastní styly (viz [Stylování](#stylování)), výchozí je `GridStyle` |

---

## Sloupce

Každý `addColumn*()` vrací instanci sloupce, na níž lze řetězit fluent metody.

### Společné metody všech sloupců

```php
->sortAndFilter()          // zapne řazení i filtrování najednou
->setSortable()            // zapne řazení
->setFilterable()          // zapne filtrování
->setWidth(WidthClasses)   // šířka buňky (Default | Fit | Boolean)
->addClass(string|array)   // přidá CSS třídu buňce
->setLink(string $link, bool $ajax, bool $history, bool $checkEmpty)
                           // hodnota v buňce bude odkaz
->addLinkParam(string $key, string $value, bool $data)
                           // parametr odkazu (data=true → z entity)
->setManyToMany(key, value, name)  // navigace přes ManyHasMany relaci
->setPrefix(string)        // prefix před hodnotou (např. '#')
```

### `addColumnString`

```php
$grid->addColumnString(
    string    $name,                    // název property entity (lze vnořit přes '->': 'author->name')
    ?string   $label = null,           // popis sloupce, výchozí = $name
    TextAlign $align = TextAlign::Left,
    ?string   $unit  = null,           // statická jednotka za hodnotou
    bool      $unitFromData = false    // jednotka z jiné property (pak $unit = název property)
): ColumnString
```

### `addColumnNumber`

```php
$grid->addColumnNumber(
    string    $name,
    ?string   $label = null,
    TextAlign $align = TextAlign::Right,
    ?string   $unit  = null,
    bool      $unitFromData = false
): ColumnNumber
```

Navíc:

```php
->setFormat(int $decimal, string $decimalSep, string $thousandsSep)
->addSummary(SummaryType $type)   // souhrnný řádek: Sum | Avg | Min | Max
```

### `addColumnDate`

```php
$grid->addColumnDate(
    string    $name,
    ?string   $label    = null,
    DateType  $dateType = DateType::Date,
    TextAlign $align    = TextAlign::Left,
): ColumnDate
```

`DateType` hodnoty:

| Hodnota | Formát |
|---|---|
| `Date` | `d. m. y` |
| `DateTime` | `d. m. y H:i` |
| `DateShort` | `d.m` |
| `DateTimeShort` | `d.m. H:i` |
| `Time` | `H:i` |
| `YearMonth` | `Y-m` |

Filtr datumového sloupce automaticky zobrazí rozsah Od–Do. Lze také aktivovat bez filtrování:

```php
->setDateRangeFilter()
```

### `addColumnBoolean`

```php
$grid->addColumnBoolean(
    string      $name,
    ?string     $label = null,
    BooleanType $type  = BooleanType::Default,
    TextAlign   $align = TextAlign::Left,
): ColumnBoolean
```

`BooleanType` hodnoty: `Default` · `Visibility` · `Trash` · `Error`

### `addColumnEnum`

```php
$grid->addColumnEnum(
    string    $name,
    string    $label,
    string    $enumClass,    // FQCN BackedEnum
    TextAlign $align = TextAlign::Left,
): ColumnEnum
```

Filtr zobrazí select s hodnotami enumu.

### `addColumnFunction`

Volá metodu repozitáře místo přímé property entity.

```php
$col = $grid->addColumnFunction('getTotalPrice', 'Celkem');
$col->addFunctionParam('id', fromData: true);   // předá entity->id jako argument
$col->addFunctionParam(0);                       // předá konstantu 0
```

Metoda repozitáře musí mít odpovídající signaturu.

### `addColumnImage`

```php
$grid->addColumnImage(
    string    $name,
    ?string   $label      = null,
    ImageType $type       = ImageType::Small,  // Small | Medium | Large
    bool      $isRelative = true,              // cesta relativní k www/
): ColumnImage
```

### `addColumnFile`

```php
$grid->addColumnFile(
    string  $name,
    ?string $label          = null,
): ColumnFile
```

### `addColumnCheckEmpty`

Zobrazí ikonu přítomnosti/nepřítomnosti hodnoty místo samotné hodnoty.

```php
$grid->addColumnCheckEmpty(string $name, ?string $label, TextAlign $align): ColumnCheckEmpty
```

---

## Akce na řádcích

### Odkaz (`addAction`)

```php
$grid->addAction(
    ActionType $type,
    string     $link,           // Nette link, např. 'edit' nebo 'delete!'
    ?string    $title  = null,
    bool       $history = false,
    bool       $ajax    = true,
): Action
```

```php
$action->addLinkParam('id', 'id', data: true);  // přidá parametr z entity
```

### Callback (`addActionCallback`)

Akce zavolá PHP callback místo přesměrování.

```php
$action = $grid->addActionCallback(ActionType::Delete, primaryKey: 'id');
$action->onCall[] = function (int $id): void {
    $this->repository->delete($id);
};
```

### Záhlaví (`addHeaderAction`)

```php
$grid->addHeaderAction(
    ActionType $type,
    string     $link,
    ?string    $title   = null,
    bool       $ajax    = true,
    bool       $history = true,
): HeaderAction
```

### `ActionType` hodnoty

`Edit` · `Delete` · `Show` · `New` · `Duplicate` · `Archive` · `Cancel` · `Complete` · `Link` · `Export` · `Money` · `Graph` · `Print` · `Info` · `Update` · `Email` · `Close` · `Cart` · `Bulb` · `Copy` · `Truck`

---

## Filtrování

Filtr se aktivuje na konkrétním sloupci:

```php
$grid->addColumnString('name')->setFilterable();
// nebo zároveň řazení:
$grid->addColumnString('name')->sortAndFilter();
```

Řádek filtru se zobrazí automaticky, pokud je alespoň jeden sloupec filtrovatelný.

### Operátory ve filtru (textová pole)

| Prefix | Operátor |
|---|---|
| bez prefixu | LIKE contains |
| `>` | větší než |
| `<` | menší než |
| `=` | přesná shoda |
| `!` | není rovno |
| `null` (přesně) | IS NULL |

---

## Řazení

Výchozí řazení se nastaví automaticky na první přidaný sloupec. Lze přepsat:

```php
$grid->setSortColumn('createdAt');
$grid->reverseSort();   // výchozí směr DESC NULLS FIRST
```

Kliknutím na záhlaví sloupce uživatel přepíná ASC/DESC.

---

## Stránkování

```php
// Zapnutí s výchozími možnostmi (80, 160, 240) a výchozím offsetem #1
$grid->setPagination();

// Vlastní možnosti
$grid->setItemsPerPageOptions([25, 50, 100], itemsPerPageOffset: 1);
```

---

## Export do Excelu

```php
$grid->addExport();                      // tlačítko s výchozím názvem 'Export'
$grid->addExport('Přehled zakázek');     // vlastní název souboru

// Vlastní výstupní adresář (volitelné, výchozí: www/export/)
$grid->setExportPaths(
    basePath:   '/var/www/app/',
    exportPath: '/var/www/app/www/export/'
);

// Callback po exportu (dostane název souboru)
$grid->onExport[] = function (string $filename): void {
    // logování, notifikace…
};
```

Export vždy zahrnuje aktuálně filtrovaná a seřazená data (bez stránkování).

---

## Popis / caption

```php
$grid->setCaption('Seznam zákazníků');
```

---

## Hover efekt řádků

```php
$grid->hoverable = true;
```

---

## Stylování

Všechny CSS třídy jsou odděleny do `GridStyleInterface`. Výchozí implementace `GridStyle` používá Tailwind CSS.

Pro vlastní styly implementujte rozhraní a předejte instanci do konstruktoru:

```php
class MyGridStyle implements GridStyleInterface
{
    public function mainContainer(): string { return 'my-grid-wrapper'; }
    // … ostatní metody
}

$grid = new Grid($data, new MyGridStyle());
```

---

## Vnořené property a relace

Sloupce podporují tečkovou notaci (oddělovač `->`) pro přístup k vnořeným entitám:

```php
$grid->addColumnString('customer->company->name', 'Firma');
```

Pro `ManyHasMany` relace:

```php
$grid->addColumnString('tags->name')
     ->setManyToMany(key: 'type', value: 'primary', name: 'label');
```
