# Declarative UI ve Imperative UI Karşılaştırması

## Imperative UI: "Her Adımı Sen Söyle"

Yazılımda bir arayüz oluşturmanın iki farklı yolu vardır. Bunlardan ilki, **imperative** (emirli / adım adım) yaklaşımdır.

Bu yaklaşımda yazılımcı, ekranda bir şeyin nasıl görüneceğini değil, **nasıl değiştirileceğini** adım adım tarif eder. Yani "şu an ekranda ne var?" sorusunu sormak yerine, "bu düğmeyi bul, rengini değiştir, sonra metni güncelle, ardından göster" gibi komutlar verilir.

Bunu bir LEGO talimatı kitabına benzetmek mümkündür. Talimat kitabı, parçaları tek tek hangi sırayla nereye yerleştireceğini söyler. Bir şeyi değiştirmek istiyorsanız, önce eski parçayı söküp yerine yeniyi takmanız gerekir.

Android'in eski XML tabanlı sistemi bu yaklaşımla çalışıyordu. Önce XML dosyasında arayüz tasarlanırdı, ardından Kotlin/Java kodundan bu arayüz elemanlarına tek tek ulaşılarak değiştirilirdi:

```kotlin
// MainActivity.kt - Imperative Yaklaşım (Eski XML Yöntemi - Karşılaştırma İçin)

// Bu kod Jetpack Compose kullanmaz; sadece farkı göstermek için burada.
// Bir TextView'ın metnini değiştirmek için şu adımlar gerekirdi:

// 1. XML'deki elemanı bul
val textView = findViewById<TextView>(R.id.myTextView)

// 2. Düğmeyi bul
val button = findViewById<Button>(R.id.myButton)

// 3. Düğmeye tıklandığında ne olacağını tek tek söyle
button.setOnClickListener {
    textView.text = "Merhaba!"       // Metni değiştir
    textView.setTextColor(Color.RED) // Rengini değiştir
    textView.visibility = View.VISIBLE // Görünür yap
}
```

Görüldüğü gibi, her değişiklik için ayrı bir komut yazılmaktadır. Uygulama büyüdükçe bu komutların sayısı artar ve kod karmaşık bir hal alır. "Hangi elemanı değiştirdim? Bunu zaten değiştirmiş miydim?" gibi sorular hata yapmayı kolaylaştırır.

---

## Declarative UI: "Sonucu Sen Tarif Et"

İkinci yaklaşım, **declarative** (tanımlayıcı / ne göstereceğini söyle) yaklaşımdır. Jetpack Compose bu yaklaşımı kullanır.

Bu yaklaşımda yazılımcı, "şu an ekranda ne görünmeli?" sorusunu yanıtlar. Değiştirme, güncelleme veya bulma işlemleriyle uğraşılmaz. Sadece **mevcut duruma göre ekranın nasıl görünmesi gerektiği** tarif edilir, gerisini Compose halleder.

Bunu bir restorana gitmeye benzetmek mümkündür. Garsondan tek tek "önce tabağı getir, sonra çataldı al, ardından yemeği koy" demek yerine sadece "mantı istiyorum" dersiniz. Nasıl geleceğini siz değil, mutfak halleder.

```kotlin
// MainActivity.kt - Declarative Yaklaşım (Jetpack Compose)

package com.example.declarativeornek

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Text
import androidx.compose.material3.Button
import androidx.compose.foundation.layout.Column
import androidx.compose.runtime.*

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SelamlayiciEkran()
        }
    }
}

@Composable
fun SelamlayiciEkran() {
    // "Durum" burada tutulur: düğmeye basıldı mı, basılmadı mı?
    var dugmeyeBasildi by remember { mutableStateOf(false) }

    Column {
        // Ekranda ne görüneceği, duruma göre buradan tarif edilir.
        // "Eğer düğmeye basıldıysa 'Merhaba!' göster, basılmadıysa 'Bekliyor...' göster."
        Text(
            text = if (dugmeyeBasildi) "Merhaba!" else "Bekliyor..."
        )

        Button(onClick = { dugmeyeBasildi = true }) {
            Text("Selamla")
        }
    }
}
```

Bu kodda hiçbir "bul ve değiştir" işlemi yapılmamaktadır. Sadece "durum şuysa ekran şöyle görünsün" denilmektedir. `dugmeyeBasildi` değişkeni `true` olduğunda Compose, metni otomatik olarak güncelleyerek yeniden çizer.

---

## İki Yaklaşımın Farkı: Özet

Farkı somutlaştırmak için şöyle düşünülebilir:

| | Imperative | Declarative |
|---|---|---|
| **Soru** | "Nasıl değiştireyim?" | "Ne göstermeliyim?" |
| **Yaklaşım** | Adım adım komut | Duruma göre tarif |
| **Benzetme** | LEGO talimat kitabı | Restoran siparişi |
| **Örnek** | `textView.text = "..."` | `Text(if (durum) "A" else "B")` |

Jetpack Compose, declarative yaklaşımı benimsediği için kod hem daha az satır içerir hem de okunması daha kolaydır. Ekrandaki her şey, **o anki duruma** göre kendiliğinden güncellenir.

---

## Pekiştirme Uygulaması

Aşağıdaki uygulama bu iki kavramı doğrudan göstermektedir. Düğmeye her basıldığında sayaç artmakta ve metin kendiliğinden güncellenmektedir. Hiçbir "bul ve değiştir" kodu bulunmamaktadır:

```kotlin
// MainActivity.kt - Declarative UI Pekiştirme

package com.example.sayacornek

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Text
import androidx.compose.material3.Button
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SayacEkrani()
        }
    }
}

@Composable
fun SayacEkrani() {
    var sayi by remember { mutableStateOf(0) }

    Column(horizontalAlignment = Alignment.CenterHorizontally) {
        Spacer(modifier = Modifier.height(48.dp))

        // Durum ne ise, metin onu gösterir. Başka bir komut gerekmez.
        Text(
            text = "Sayaç: $sayi",
            fontSize = 32.sp
        )

        Spacer(modifier = Modifier.height(24.dp))

        Button(onClick = { sayi++ }) {
            Text("Artır")
        }
    }
}
```

`sayi` değişkeni her değiştiğinde Compose, `SayacEkrani` fonksiyonunu otomatik olarak yeniden çalıştırır ve ekranı günceller. Bu sürece **recomposition** (yeniden oluşturma) adı verilir; bir sonraki konuda bu kavram ayrıntılı ele alınacaktır.



# @Composable Fonksiyon Nedir? İlk Composable Yazma

## Fonksiyon Kavramına Kısa Bir Bakış

Kotlin'de bir fonksiyon, belirli bir işi yapan ve isim verilmiş bir kod bloğudur. `fun` anahtar kelimesiyle tanımlanır ve istenildiğinde çağrılarak çalıştırılır. Bu kavram Kotlin derslerinde ele alınmıştı.

Jetpack Compose'da ise özel bir fonksiyon türü karşımıza çıkar: **@Composable fonksiyon**.

---

## @Composable Nedir?

Normal bir Kotlin fonksiyonu bir hesaplama yapar ya da bir değer döndürür. @Composable fonksiyon ise bunlardan farklı olarak **ekranda bir şey çizer**.

Başına eklenen `@Composable` ifadesi bir **anotasyondur**. Bu anotasyon, Kotlin derleyicisine şunu söyler: "Bu fonksiyon sıradan bir fonksiyon değil; ekrana bir UI parçası çizecek."

Bunu bir damgaya benzetmek mümkündür. Bir kağıdın üzerine "GİZLİ" damgası vurulduğunda o kağıt artık farklı bir şekilde işlenir. `@Composable` damgası da fonksiyona vurulduğunda Compose sistemi onu farklı bir şekilde işlemeye başlar.

```kotlin
// Bu sıradan bir Kotlin fonksiyonudur. Ekranda hiçbir şey göstermez.
fun topla(a: Int, b: Int): Int {
    return a + b
}

// Bu ise bir Composable fonksiyondur. Ekranda bir metin gösterir.
@Composable
fun SelamVer() {
    Text("Merhaba Dünya!")
}
```

İki fonksiyon arasındaki tek görünür fark, başındaki `@Composable` anotasyonudur. Ancak bu küçük fark, fonksiyonun nasıl davrandığını tamamen değiştirir.

---

## @Composable Fonksiyonların Kuralları

Bir @Composable fonksiyon yazılırken dikkat edilmesi gereken birkaç temel kural vardır:

**1. Yalnızca @Composable içinden çağrılabilir.**
Normal bir Kotlin fonksiyonunun içinden @Composable bir fonksiyon çağrılamaz. Bir @Composable, yalnızca başka bir @Composable içinden ya da `setContent { }` bloğundan çağrılabilir.

**2. Genellikle bir değer döndürmez.**
@Composable fonksiyonlar ekrana bir şey çizer; hesap yapıp sonuç döndürmez. Bu nedenle dönüş tipi çoğunlukla `Unit`'tir (yani "hiçbir şey döndürmez").

**3. İsimler büyük harfle başlar.**
Kotlin'de normal fonksiyonlar küçük harfle başlar (`topla`, `hesapla`). @Composable fonksiyonlar ise büyük harfle başlar (`SelamVer`, `AnaSayfa`). Bu bir zorunluluk değil, ama herkesin uyduğu güçlü bir kuraldır.

---

## İlk Composable: Adım Adım

Aşağıda bir Android uygulamasında ilk @Composable fonksiyonun nasıl yazıldığı gösterilmektedir:

```kotlin
// MainActivity.kt

package com.example.ilkcomposable

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // setContent, Compose dünyasının kapısıdır.
        // Buradan itibaren her şey @Composable fonksiyonlarla çizilir.
        setContent {
            SelamVer() // @Composable fonksiyon burada çağrılıyor
        }
    }
}

// @Composable anotasyonu bu fonksiyonu "ekran çizen" bir fonksiyon yapar.
@Composable
fun SelamVer() {
    Text(text = "Merhaba Dünya!")
}
```

Burada `setContent { }` bloğu, Activity ile Compose arasındaki köprüdür. Bu blok içine yazılan her şey artık Compose tarafından yönetilir.

`Text(...)` de bir @Composable fonksiyondur; Compose kütüphanesinin içinde tanımlanmıştır ve ekrana metin çizer. `SelamVer` ise bu hazır fonksiyonu kullanan, kendi yazdığımız @Composable fonksiyondur.

---

## Composable Fonksiyonları Birleştirme

@Composable fonksiyonların en güçlü yanı, **birbirinin içine yerleştirilebilmesidir**. Küçük parçalar bir araya getirilerek daha büyük arayüzler oluşturulur.

Bunu LEGO parçalarına benzetmek mümkündür. Her @Composable bir LEGO parçasıdır. Bu parçalar birbirine takılarak istenen yapı elde edilir. Parçalardan biri değiştiğinde tüm yapıyı yeniden kurmak gerekmez; sadece o parça değiştirilir.

```kotlin
// MainActivity.kt

package com.example.birlestirme

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            KullaniciBilgisi() // Büyük parça çağrılıyor
        }
    }
}

// Küçük parça: sadece bir isim gösterir
@Composable
fun KullanicıAdi() {
    Text(text = "Ali Yılmaz")
}

// Küçük parça: sadece bir yaş gösterir
@Composable
fun KullaniciYasi() {
    Text(text = "Yaş: 28")
}

// Büyük parça: küçük parçaları bir araya getirir
@Composable
fun KullaniciBilgisi() {
    Column {
        KullanicıAdi()  // İlk parça buraya yerleştiriliyor
        KullaniciYasi() // İkinci parça buraya yerleştiriliyor
    }
}
```

`KullaniciBilgisi`, `KullanicıAdi` ve `KullaniciYasi` fonksiyonlarını içinde barındırır. `Column` ise bu iki parçayı alt alta dizer; o da bir @Composable fonksiyondur.

---

## @Composable Fonksiyonlara Parametre Göndermek

Normal Kotlin fonksiyonlarına olduğu gibi, @Composable fonksiyonlara da parametre gönderilebilir. Bu sayede aynı fonksiyon farklı verilerle farklı sonuçlar üretir.

```kotlin
// MainActivity.kt

package com.example.parametreli

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            Column {
                // Aynı fonksiyon, farklı parametrelerle üç kez çağrılıyor
                KisiKarti(isim = "Ali", sehir = "Ankara")
                KisiKarti(isim = "Ayşe", sehir = "İzmir")
                KisiKarti(isim = "Mehmet", sehir = "İstanbul")
            }
        }
    }
}

@Composable
fun KisiKarti(isim: String, sehir: String) {
    Text(text = "$isim - $sehir")
}
```

`KisiKarti` fonksiyonu üç kez çağrılmış, ancak her seferinde farklı bir isim ve şehir göstermiştir. Aynı LEGO kalıbından farklı renklerle farklı parçalar üretmek gibidir: kalıp tektir, ama sonuç her seferinde farklıdır.

---

## Özet

`@Composable` anotasyonu, sıradan bir Kotlin fonksiyonunu ekran çizen bir yapı taşına dönüştürür. Bu fonksiyonlar birbirinin içine yerleştirilebilir, parametre alabilir ve her biri uygulamanın küçük ama bağımsız bir parçasını temsil eder. Tüm Jetpack Compose arayüzleri, bu yapı taşlarının bir araya getirilmesiyle oluşur.





# Preview Kullanımı: @Preview Anotasyonu

## @Preview Nedir?

Bir @Composable fonksiyon yazıldıktan sonra nasıl göründüğünü anlamak için uygulamayı emülatörde ya da fiziksel cihazda çalıştırmak gerekir. Ancak her küçük değişiklik için uygulamayı baştan derleyip çalıştırmak zaman alır.

`@Preview` anotasyonu bu sorunu çözer. Bir @Composable fonksiyonun üzerine `@Preview` eklendiğinde, Android Studio o fonksiyonu **kod editörünün yanında, cihaza ihtiyaç duymadan** görsel olarak gösterir.

Bunu bir resim çerçevesine benzetmek mümkündür. Bir ressam tablosunu müzeye asmadan önce küçük bir taslak çizer ve nasıl göründüğünü kontrol eder. `@Preview` de aynı işlevi görür: uygulamayı çalıştırmadan önce arayüzün taslağını gösterir.

---

## @Preview Nasıl Yazılır?

`@Preview` anotasyonu, önizlemesi istenen @Composable fonksiyonun hemen üstüne yazılır. Ayrıca `@Composable` anotasyonu da birlikte kullanılmalıdır; çünkü `@Preview` de teknik olarak bir @Composable içinde çalışır.

```kotlin
// MainActivity.kt

package com.example.previewornek

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.tooling.preview.Preview

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SelamVer()
        }
    }
}

@Composable
fun SelamVer() {
    Text(text = "Merhaba Dünya!")
}

// @Preview anotasyonu bu fonksiyonu Android Studio'da görünür kılar.
// Bu fonksiyon yalnızca önizleme içindir; uygulamanın içinde çağrılmaz.
@Preview
@Composable
fun SelamVerOnizleme() {
    SelamVer()
}
```

Dikkat edilmesi gereken önemli bir nokta vardır: önizleme fonksiyonu (`SelamVerOnizleme`) asıl fonksiyondan (`SelamVer`) **ayrıdır**. Asıl fonksiyon uygulamada çalışır; önizleme fonksiyonu ise yalnızca Android Studio'nun tasarım panelinde görüntülenir, uygulamaya dahil edilmez.

---

## @Preview Parametreleri

`@Preview` anotasyonu tek başına kullanılabileceği gibi, çeşitli parametreler alarak önizlemeyi özelleştirmek de mümkündür.

### `showBackground`

Varsayılan olarak önizleme şeffaf bir arka plan üzerinde gösterilir. `showBackground = true` parametresi eklendiğinde beyaz bir arka plan eklenir; bu da arayüzü daha net görmek için faydalıdır.

```kotlin
// MainActivity.kt

package com.example.previewarka

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.tooling.preview.Preview

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            BilgiMetni()
        }
    }
}

@Composable
fun BilgiMetni() {
    Text(text = "Jetpack Compose öğreniyorum.")
}

// Beyaz arka planla önizleme
@Preview(showBackground = true)
@Composable
fun BilgiMetniOnizleme() {
    BilgiMetni()
}
```

### `name`

Birden fazla önizleme kullanıldığında her birine isim vermek, hangi önizlemenin neyi gösterdiğini anlamayı kolaylaştırır.

```kotlin
@Preview(name = "Kısa Metin Önizlemesi", showBackground = true)
@Composable
fun KisaMetinOnizleme() {
    BilgiMetni()
}
```

### `widthDp` ve `heightDp`

Önizlemenin genişliği ve yüksekliği piksel yerine `dp` birimiyle belirlenir. Bu sayede farklı ekran boyutları simüle edilebilir.

```kotlin
@Preview(showBackground = true, widthDp = 200, heightDp = 100)
@Composable
fun KucukEkranOnizleme() {
    BilgiMetni()
}
```

---

## Birden Fazla @Preview Kullanmak

Aynı @Composable fonksiyon için birden fazla `@Preview` tanımlanabilir. Bu, aynı bileşenin farklı boyutlarda ya da farklı koşullarda nasıl göründüğünü yan yana karşılaştırmak için çok kullanışlıdır.

Bunu bir fotoğrafçının aynı konuyu farklı açılardan çekmesine benzetmek mümkündür. Konu aynıdır; ancak her fotoğraf farklı bir bakış açısı sunar.

```kotlin
// MainActivity.kt

package com.example.coklupreview

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.tooling.preview.Preview

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            UrunBasligi(baslik = "Kulaklık")
        }
    }
}

@Composable
fun UrunBasligi(baslik: String) {
    Text(text = baslik)
}

// Geniş ekran önizlemesi
@Preview(name = "Geniş Ekran", showBackground = true, widthDp = 320)
@Composable
fun GenisEkranOnizleme() {
    UrunBasligi(baslik = "Kulaklık")
}

// Dar ekran önizlemesi
@Preview(name = "Dar Ekran", showBackground = true, widthDp = 150)
@Composable
fun DarEkranOnizleme() {
    UrunBasligi(baslik = "Kulaklık")
}
```

Android Studio, bu iki önizlemeyi aynı anda tasarım panelinde gösterir. Böylece aynı bileşenin dar ve geniş ekranlarda nasıl davrandığı tek bakışta görülür.

---

## @Preview Fonksiyonları Nereye Yazılmalı?

`@Preview` fonksiyonları genellikle aynı dosyanın en altına ya da ayrı bir `Preview.kt` dosyasına yazılır. Bu fonksiyonlar derleme sırasında uygulamaya dahil edilmez; yalnızca Android Studio'nun tasarım aracı tarafından kullanılır.

Bu nedenle `@Preview` fonksiyonları içinde gerçek veri kullanmak yerine **örnek (sahte) veri** kullanmak doğru bir alışkanlıktır:

```kotlin
// MainActivity.kt

package com.example.sahteveri

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.tooling.preview.Preview

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            // Gerçek uygulamada veriler bir veri tabanından ya da
            // API'den gelir. Şimdilik sabit veri kullanılıyor.
            KullanicıKarti(isim = "Zeynep Kaya", yas = 24)
        }
    }
}

@Composable
fun KullanicıKarti(isim: String, yas: Int) {
    Column {
        Text(text = "İsim: $isim")
        Text(text = "Yaş: $yas")
    }
}

// Önizlemede sahte (örnek) veri kullanılır.
@Preview(showBackground = true)
@Composable
fun KullanicıKartiOnizleme() {
    KullanicıKarti(isim = "Örnek Kullanıcı", yas = 20)
}
```

---

## Özet

`@Preview` anotasyonu, @Composable fonksiyonların uygulamayı çalıştırmadan Android Studio içinde görsel olarak kontrol edilmesini sağlar. `showBackground`, `name`, `widthDp` ve `heightDp` gibi parametrelerle önizleme özelleştirilebilir. Aynı bileşen için birden fazla önizleme tanımlanarak farklı koşullar karşılaştırılabilir. Bu araç, geliştirme sürecini hem hızlandırır hem de hata yapma olasılığını azaltır.



# Recomposition Kavramı ve Nasıl Çalışır

## Ekran Nasıl Güncellenir?

Bir uygulama çalışırken ekrandaki veriler değişir. Kullanıcı bir düğmeye basar, bir liste güncellenir ya da bir sayaç artar. Bu değişiklikler olduğunda ekranın da güncellenmesi gerekir.

Jetpack Compose'un bu güncellemeyi nasıl yaptığını anlamak, Compose'un çalışma mantığını kavramanın temelidir.

---

## Recomposition Nedir?

**Recomposition** (yeniden oluşturma), bir @Composable fonksiyonun içindeki veri değiştiğinde Compose tarafından **otomatik olarak yeniden çağrılmasıdır**.

Bunu bir beyaz tahta düşünerek anlamak mümkündür. Tahtaya bir şey yazılır, sonra bir bilgi değişir. Eski bilgiyi silip yalnızca değişen kısmı düzeltmek mümkündür; tahtanın tamamını silip yeniden yazmak gerekmez. Compose da tam olarak bunu yapar: yalnızca **değişen kısımları** yeniden çizer, geri kalanına dokunmaz.

---

## State: Değişimi Tetikleyen Güç

Recomposition'ın gerçekleşmesi için Compose'un değişikliği **fark etmesi** gerekir. Normal bir Kotlin değişkeni değiştiğinde Compose bundan haberdar olmaz. Compose'un takip edebildiği özel bir değişken türü kullanılması gerekir: **State**.

`mutableStateOf()` ile oluşturulan bir değişken değiştiğinde, Compose bunu otomatik olarak algılar ve o state'i kullanan @Composable fonksiyonları yeniden çalıştırır.

```kotlin
// MainActivity.kt

package com.example.recompositiontemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Button
import androidx.compose.material3.Text
import androidx.compose.foundation.layout.Column
import androidx.compose.runtime.*
import androidx.compose.ui.tooling.preview.Preview

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SayacEkrani()
        }
    }
}

@Composable
fun SayacEkrani() {
    // Bu sıradan bir Kotlin değişkeni DEĞİLdir.
    // Compose tarafından takip edilen özel bir state değişkenidir.
    var sayi by remember { mutableStateOf(0) }

    Column {
        Text(text = "Sayı: $sayi")

        Button(onClick = { sayi++ }) {
            Text("Artır")
        }
    }
}

@Preview(showBackground = true)
@Composable
fun SayacEkraniOnizleme() {
    SayacEkrani()
}
```

Düğmeye her basıldığında `sayi` değişkeni artar. Compose bu değişikliği fark eder ve `SayacEkrani` fonksiyonunu yeniden çalıştırır. Ekrandaki `Text` bileşeni yeni değeri gösterecek şekilde güncellenir. Tüm bunlar **otomatik olarak** gerçekleşir; başka bir komut yazmak gerekmez.

---

## `remember` Neden Gereklidir?

Recomposition sırasında @Composable fonksiyon yeniden çalışır. Bu yeniden çalışma sırasında fonksiyonun içindeki tüm değişkenler normalde sıfırdan oluşturulur. Yani sayaç her güncellendiğinde sıfırlanırdı.

`remember`, Compose'a şunu söyler: "Bu değişkeni her seferinde sıfırdan oluşturma; önceki değerini hatırla."

Bunu bir not defterine benzetmek mümkündür. `remember` olmadan her recomposition'da değişken unutulur; tıpkı her gün aynı sayfayı yeniden yazmak gibi. `remember` eklendiğinde ise Compose o değeri not defterinde saklar ve recomposition sonrasında kaldığı yerden devam eder.

```kotlin
// MainActivity.kt

package com.example.rememberfarki

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.material3.Button
import androidx.compose.material3.Text
import androidx.compose.runtime.*
import androidx.compose.ui.tooling.preview.Preview

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            Column {
                HatirlayanSayac()
            }
        }
    }
}

@Composable
fun HatirlayanSayac() {
    // remember sayesinde bu değer recomposition'dan sonra korunur.
    var hatirlayanSayi by remember { mutableStateOf(0) }

    Column {
        Text(text = "Hatırlayan sayaç: $hatirlayanSayi")
        Button(onClick = { hatirlayanSayi++ }) {
            Text("Artır")
        }
    }
}

@Preview(showBackground = true)
@Composable
fun HatirlayanSayacOnizleme() {
    HatirlayanSayac()
}
```

---

## Compose Yalnızca Gerekeni Yeniden Çizer

Recomposition'ın en önemli özelliklerinden biri **akıllı** olmasıdır. Compose, bir state değiştiğinde uygulamanın tamamını değil, yalnızca **o state'i kullanan @Composable fonksiyonları** yeniden çizer.

Bunu bir gazete baskısına benzetmek mümkündür. Gazetenin yalnızca birinci sayfasında bir hata varsa tüm gazeteyi yeniden basmak gerekmez; sadece o sayfa yeniden basılır. Compose da değişmeyen bileşenlere dokunmaz.

Aşağıdaki örnekte iki ayrı bölüm bulunmaktadır. Yalnızca biri güncellenir; Compose diğerini yeniden çizmez:

```kotlin
// MainActivity.kt

package com.example.akillirecomposition

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.material3.Button
import androidx.compose.material3.Text
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AnaEkran()
        }
    }
}

@Composable
fun AnaEkran() {
    var tiklamaSayisi by remember { mutableStateOf(0) }

    Column {
        // Bu bileşen hiç değişmez; Compose onu yeniden çizmez.
        SabitBaslik()

        Spacer(modifier = Modifier.height(16.dp))

        // Bu bileşen state'e bağlıdır; yalnızca o yeniden çizilir.
        DinamikSayac(sayi = tiklamaSayisi)

        Spacer(modifier = Modifier.height(16.dp))

        Button(onClick = { tiklamaSayisi++ }) {
            Text("Artır")
        }
    }
}

// Bu fonksiyon hiçbir state kullanmaz.
// Recomposition sırasında Compose bunu yeniden çizmez.
@Composable
fun SabitBaslik() {
    Text(
        text = "Recomposition Demosu",
        fontSize = 20.sp
    )
}

// Bu fonksiyon state'e bağlıdır.
// sayi değiştiğinde yalnızca bu fonksiyon yeniden çalışır.
@Composable
fun DinamikSayac(sayi: Int) {
    Text(
        text = "Tıklama sayısı: $sayi",
        fontSize = 16.sp
    )
}

@Preview(showBackground = true)
@Composable
fun AnaEkranOnizleme() {
    AnaEkran()
}
```

Düğmeye basıldığında `tiklamaSayisi` artar. Compose yalnızca `DinamikSayac` fonksiyonunu yeniden çizer. `SabitBaslik` fonksiyonu değişmediği için ona dokunulmaz. Bu davranış, uygulamanın performanslı çalışmasını sağlar.

---

## Recomposition'ın Özeti

| Kavram | Açıklama |
|---|---|
| **Recomposition** | State değiştiğinde @Composable fonksiyonun otomatik yeniden çalışması |
| **State** | Compose'un takip edebildiği özel değişken türü (`mutableStateOf`) |
| **remember** | Recomposition sırasında state değerinin korunmasını sağlar |
| **Akıllı güncelleme** | Compose yalnızca değişen kısımları yeniden çizer |

Recomposition, Jetpack Compose'un deklaratif yapısının temel motorudur. State değişir, Compose fark eder, yalnızca gereken kısımları yeniden çizer. Geliştirici bu süreci manuel olarak yönetmek zorunda kalmaz.



# setContent ve Compose'un Activity ile İlişkisi

## Activity Nedir?

Bir Android uygulaması açıldığında ekranda görünen her şey bir **Activity** içinde yaşar. Activity, uygulamanın bir ekranını temsil eden ve Android işletim sistemi tarafından yönetilen yapıdır.

Bunu bir tiyatro sahnesi olarak düşünmek mümkündür. Sahne (Activity), oyunun oynandığı yerdir. Dekorlar, oyuncular ve ışıklar sahnenin üzerinde durur. Sahne olmadan hiçbir şey izleyiciye gösterilemez.

Jetpack Compose ile yazılan bir uygulamada da tüm @Composable fonksiyonlar bir Activity'nin içinde çalışır. Ancak Activity ile Compose arasında bir köprüye ihtiyaç vardır. Bu köprünün adı `setContent`'tir.

---

## setContent Nedir?

`setContent`, bir Activity'ye "artık bu ekranı Compose ile çiz" diyen özel bir fonksiyondur. Bu fonksiyon çağrıldıktan sonra içine yazılan tüm @Composable fonksiyonlar ekrana çizilir.

Bunu bir sahne yönetmenine benzetmek mümkündür. Yönetmen (Activity), sahneyi hazırlar ve "başlayın" der. `setContent` de tam olarak bu "başlayın" komutudur; bu noktadan itibaren sahneyi Compose devralır.

`setContent` olmadan @Composable fonksiyonlar çalıştırılamaz. O, tüm Compose dünyasının başlangıç noktasıdır.

```kotlin
// MainActivity.kt

package com.example.setcontenttemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // setContent bu noktadan itibaren ekranı Compose'a devreder.
        // Süslü parantezlerin içi artık Compose'un alanıdır.
        setContent {
            IlkEkran()
        }
    }
}

@Composable
fun IlkEkran() {
    Text(text = "Activity ile Compose burada buluşur.")
}
```

`onCreate`, Activity her oluşturulduğunda Android tarafından çağrılan bir fonksiyondur. `setContent` bu fonksiyonun içine yazılır; böylece ekran ilk açıldığında Compose devreye girer.

---

## Activity ve Compose Arasındaki Görev Dağılımı

Activity ve Compose birbirinin rakibi değil, birbirini tamamlayan iki yapıdır. Her birinin kendine ait görevleri vardır:

| Görev | Kimin Sorumluluğu |
|---|---|
| Uygulamayı başlatma | Activity |
| İzin isteme | Activity |
| Ekranı çizme | Compose |
| Bileşenleri yönetme | Compose |
| Yaşam döngüsünü yönetme | Activity |

Activity bir kez `setContent` çağırdıktan sonra ekranla ilgili işlerin büyük bölümünü Compose üstlenir. Activity arka planda çalışmaya devam eder ancak arayüzün içine karışmaz.

```kotlin
// MainActivity.kt

package com.example.gorevdagilimi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Activity'nin görevi burada biter:
        // Compose'u başlatmak ve ekranı devretmek.
        setContent {
            UygulamaEkrani()
        }
    }
}

// Buradan itibaren her şey Compose'un sorumluluğundadır.
@Composable
fun UygulamaEkrani() {
    Column {
        Text(
            text = "Bu ekranı Compose çiziyor.",
            fontSize = 18.sp
        )
        Spacer(modifier = Modifier.height(8.dp))
        Text(
            text = "Activity yalnızca başlangıç komutunu verdi.",
            fontSize = 14.sp
        )
    }
}
```

---

## ComponentActivity Nereden Gelir?

`MainActivity`, `ComponentActivity` sınıfından türetilir. Bu, Jetpack kütüphanelerinin Activity ile uyumlu çalışmasını sağlayan temel sınıftır. `setContent` fonksiyonu da bu sınıfın bir parçası olarak gelir; doğrudan Activity içinde kullanılabilmesinin nedeni budur.

Bunu bir prize benzetmek mümkündür. `ComponentActivity`, duvardaki prizdir. `setContent` ise bu prize takılan fiştir. Compose akımı, bu bağlantı kurulduğunda akmaya başlar.

```kotlin
// MainActivity.kt

package com.example.componentactivity

import android.os.Bundle
import androidx.activity.ComponentActivity   // <-- Temel sınıf buradan gelir
import androidx.activity.compose.setContent  // <-- setContent buradan gelir
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.tooling.preview.Preview

// MainActivity, ComponentActivity'den türetilir.
// Bu sayede setContent kullanılabilir hale gelir.
class MainActivity : ComponentActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        setContent {
            KarsilamaMesaji()
        }
    }
}

@Composable
fun KarsilamaMesaji() {
    Text(text = "ComponentActivity ve setContent birlikte çalışıyor.")
}

@Preview(showBackground = true)
@Composable
fun KarsilamaMesajiOnizleme() {
    KarsilamaMesaji()
}
```

---

## Özet

`setContent`, Activity ile Compose arasındaki tek bağlantı noktasıdır. Activity uygulamayı başlatır, `setContent` ile ekranı Compose'a devreder ve bundan sonra arayüzle ilgili tüm işler @Composable fonksiyonlar tarafından yürütülür. Bu görev dağılımı, Compose'un bağımsız, test edilebilir ve yeniden kullanılabilir bileşenler üretmesini mümkün kılar.


# Alıştırma: İlk "Merhaba Dünya" Composable

## Alıştırmanın Amacı

Bu alıştırmada bu derse kadar öğrenilen tüm kavramlar tek bir uygulama içinde bir araya getirilir. Hedef; bir `MainActivity` oluşturmak, `setContent` ile Compose'u başlatmak, bir @Composable fonksiyon yazmak ve sonucu `@Preview` ile kontrol etmektir.

---

## Adım 1: Projeyi Hazırla

Android Studio'da yeni bir proje oluşturulur. Şablon olarak **Empty Activity** seçilir. Proje adı `MerhabaDunya`, paket adı `com.example.merhabadunya` olarak belirlenir. Dil olarak **Kotlin**, minimum SDK olarak **API 24** seçilir.

Proje oluşturulduktan sonra `MainActivity.kt` dosyası açılır. Dosyanın içinde varsayılan olarak gelen kodlar temizlenir ve sıfırdan yazılmaya başlanır.

---

## Adım 2: Kodu Yaz

Aşağıdaki kod, bu derste öğrenilen dört kavramın tamamını kullanır: `ComponentActivity`, `setContent`, `@Composable` ve `@Preview`.

```kotlin
// MainActivity.kt

package com.example.merhabadunya

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.sp

// Adım 1: MainActivity, ComponentActivity'den türetilir.
class MainActivity : ComponentActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Adım 2: setContent ile ekran Compose'a devredilir.
        setContent {
            MerhabaEkrani()
        }
    }
}

// Adım 3: @Composable fonksiyon tanımlanır.
// Bu fonksiyon ekranın tam ortasına bir metin çizer.
@Composable
fun MerhabaEkrani() {
    Box(
        modifier = Modifier.fillMaxSize(),
        contentAlignment = Alignment.Center
    ) {
        Text(
            text = "Merhaba Dünya!",
            fontSize = 32.sp,
            fontWeight = FontWeight.Bold
        )
    }
}

// Adım 4: @Preview ile sonuç cihaza gerek kalmadan kontrol edilir.
@Preview(showBackground = true)
@Composable
fun MerhabaEkraniOnizleme() {
    MerhabaEkrani()
}
```

---

## Kodun Açıklaması

Kodun içinde birkaç yeni bileşen dikkat çekmektedir.

**`Box` ve `fillMaxSize`:** `Box`, içine yerleştirilen bileşenleri üst üste ya da hizalanmış biçimde gösterir. `Modifier.fillMaxSize()` ise bu kutunun ekranın tamamını kaplamasını sağlar. `contentAlignment = Alignment.Center` parametresi, içerideki `Text` bileşenini tam ortaya hizalar.

Bunu boş bir oda olarak düşünmek mümkündür. `Box` odanın kendisidir, `fillMaxSize` odanın tüm alanı kaplamasını sağlar, `Alignment.Center` ise mobilyanın odanın tam ortasına yerleştirilmesini söyler.

**`fontSize` ve `fontWeight`:** Bu iki parametre metnin görünümünü belirler. `32.sp` metnin boyutunu, `FontWeight.Bold` ise kalınlığını ayarlar. `sp` birimi, kullanıcının telefon ayarlarındaki yazı boyutu tercihine saygı göstererek ölçeklenen bir birimdir.

---

## Sonuç

Kod çalıştırıldığında ya da `@Preview` panelinde görüntülendiğinde ekranın tam ortasında kalın ve büyük harflerle **"Merhaba Dünya!"** yazısı belirir.

Bu tek dosya, Jetpack Compose ile yazılmış eksiksiz ve çalışan bir Android uygulamasıdır. `ComponentActivity`, `setContent`, `@Composable` ve `@Preview` — bu dört yapı, bundan sonra yazılacak her Compose uygulamasının da temelini oluşturacaktır.




# Text Composable: style, color, fontSize, fontWeight

## Text Composable Nedir?

Bir mobil uygulamada en sık kullanılan arayüz bileşeni metindir. Başlıklar, açıklamalar, düğme etiketleri, uyarı mesajları — bunların tamamı ekrana bir `Text` composable ile çizilir.

`Text`, Jetpack Compose'un temel yapı taşlarından biridir. Yalnızca `text` parametresi verilerek kullanılabilir; ancak görünümünü özelleştiren pek çok ek parametre de mevcuttur. Bu derste bu parametrelerin en sık kullanılanları ele alınacaktır.

---

## fontSize: Metnin Boyutu

`fontSize`, metnin ne kadar büyük görüneceğini belirler. Birim olarak `sp` (scale-independent pixels) kullanılır. `sp` birimi, kullanıcının telefon ayarlarından belirlediği yazı boyutu tercihine göre otomatik olarak ölçeklenir. Bu nedenle sabit piksel yerine her zaman `sp` kullanılması doğru bir alışkanlıktır.

Bunu bir kitaptaki başlık ve gövde metni arasındaki farka benzetmek mümkündür. Başlık büyük punto ile basılır, gövde metni daha küçüktür. `fontSize` parametresi de tam olarak bu farkı oluşturur.

```kotlin
// MainActivity.kt

package com.example.textfontsize

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            FontBoyutlari()
        }
    }
}

@Composable
fun FontBoyutlari() {
    Column(modifier = Modifier.padding(16.dp)) {
        Text(text = "Çok küçük metin", fontSize = 10.sp)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Normal metin", fontSize = 16.sp)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Büyük metin", fontSize = 24.sp)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Çok büyük metin", fontSize = 36.sp)
    }
}

@Preview(showBackground = true)
@Composable
fun FontBoyutlariOnizleme() {
    FontBoyutlari()
}
```

---

## fontWeight: Metnin Kalınlığı

`fontWeight`, metnin ince mi yoksa kalın mı görüneceğini belirler. `FontWeight` sınıfı içinde hazır değerler bulunur. En sık kullanılanları şunlardır:

| Değer | Açıklama |
|---|---|
| `FontWeight.Thin` | En ince |
| `FontWeight.Normal` | Varsayılan kalınlık |
| `FontWeight.Medium` | Orta kalınlık |
| `FontWeight.Bold` | Kalın |
| `FontWeight.ExtraBold` | Çok kalın |
| `FontWeight.Black` | En kalın |

Bunu bir kalemin baskı kuvvetine benzetmek mümkündür. Hafif basan kalem ince çizgiler bırakır, sert basan kalem kalın çizgiler bırakır. `fontWeight` de metnin bu "baskı kuvvetini" belirler.

```kotlin
// MainActivity.kt

package com.example.textfontweight

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            FontKalinliklari()
        }
    }
}

@Composable
fun FontKalinliklari() {
    Column(modifier = Modifier.padding(16.dp)) {
        Text(text = "Thin — En ince", fontSize = 18.sp, fontWeight = FontWeight.Thin)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Normal — Varsayılan", fontSize = 18.sp, fontWeight = FontWeight.Normal)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Medium — Orta", fontSize = 18.sp, fontWeight = FontWeight.Medium)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Bold — Kalın", fontSize = 18.sp, fontWeight = FontWeight.Bold)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Black — En kalın", fontSize = 18.sp, fontWeight = FontWeight.Black)
    }
}

@Preview(showBackground = true)
@Composable
fun FontKalinliklariOnizleme() {
    FontKalinliklari()
}
```

---

## color: Metnin Rengi

`color` parametresi metnin rengini belirler. Compose'da renkler `Color` sınıfı ile tanımlanır. Hazır renkler (`Color.Red`, `Color.Blue` gibi) kullanılabildiği gibi, `Color(0xFF...)` formatıyla özel renkler de tanımlanabilir.

Onaltılık (hex) renk kodları yaygın bir renk tanımlama yöntemidir. Örneğin `0xFF6200EE`, mor tonunda bir rengi ifade eder. Başındaki `0xFF` saydamlık (alfa) değerini belirtir; `FF` tamamen opak anlamına gelir.

```kotlin
// MainActivity.kt

package com.example.textcolor

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            RenkliMetinler()
        }
    }
}

@Composable
fun RenkliMetinler() {
    Column(modifier = Modifier.padding(16.dp)) {
        // Hazır renkler
        Text(text = "Kırmızı metin", fontSize = 18.sp, color = Color.Red)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Mavi metin", fontSize = 18.sp, color = Color.Blue)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Yeşil metin", fontSize = 18.sp, color = Color.Green)
        Spacer(modifier = Modifier.height(8.dp))
        // Özel hex rengi
        Text(
            text = "Özel mor metin",
            fontSize = 18.sp,
            color = Color(0xFF6200EE),
            fontWeight = FontWeight.Bold
        )
    }
}

@Preview(showBackground = true)
@Composable
fun RenkliMetinlerOnizleme() {
    RenkliMetinler()
}
```

---

## style: Tüm Özellikleri Bir Arada Yönetmek

`fontSize`, `fontWeight` ve `color` gibi parametreler tek tek verilebilir. Ancak bir uygulamada aynı metin görünümü pek çok yerde tekrar kullanıldığında her defasında tüm parametreleri yeniden yazmak hem zaman alır hem de tutarsızlığa yol açar.

`style` parametresi bu sorunu çözer. `TextStyle` sınıfı kullanılarak tüm metin özellikleri tek bir nesne içinde toplanır ve bu nesne farklı `Text` bileşenlerine uygulanır.

Bunu bir kıyafet şablonuna benzetmek mümkündür. Her gün aynı kıyafeti giymek için kıyafeti sıfırdan dikmek yerine bir şablon kullanılır. `TextStyle` de bu şablonun ta kendisidir: bir kez tanımlanır, istenen her yerde kullanılır.

```kotlin
// MainActivity.kt

package com.example.textstyle

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            StilliMetinler()
        }
    }
}

@Composable
fun StilliMetinler() {
    // Başlık stili: bir kez tanımlanır, defalarca kullanılır.
    val baslikStili = TextStyle(
        fontSize = 24.sp,
        fontWeight = FontWeight.Bold,
        color = Color(0xFF1A237E)
    )

    // Gövde metni stili
    val govdeStili = TextStyle(
        fontSize = 16.sp,
        fontWeight = FontWeight.Normal,
        color = Color(0xFF424242)
    )

    Column(modifier = Modifier.padding(16.dp)) {
        Text(text = "Birinci Başlık", style = baslikStili)
        Spacer(modifier = Modifier.height(4.dp))
        Text(
            text = "Bu başlığa ait açıklama metnidir. " +
                   "Gövde stili tüm açıklama metinlerinde kullanılır.",
            style = govdeStili
        )
        Spacer(modifier = Modifier.height(16.dp))
        Text(text = "İkinci Başlık", style = baslikStili)
        Spacer(modifier = Modifier.height(4.dp))
        Text(
            text = "İkinci başlığın açıklama metni de aynı stili taşır.",
            style = govdeStili
        )
    }
}

@Preview(showBackground = true)
@Composable
fun StilliMetinlerOnizleme() {
    StilliMetinler()
}
```

Burada `baslikStili` ve `govdeStili` değişkenleri bir kez tanımlanmış, birden fazla `Text` bileşenine uygulanmıştır. Başlık rengi değiştirilmek istendiğinde yalnızca `baslikStili` içindeki `color` değeri güncellenir; diğer tüm başlıklar otomatik olarak değişir.

---

## Özet

`Text` composable, `fontSize`, `fontWeight`, `color` ve `style` parametreleriyle tam anlamıyla özelleştirilebilir bir metin bileşenidir. Tekil değişiklikler için parametreler ayrı ayrı kullanılabilir; tutarlı ve tekrar eden görünümler içinse `TextStyle` ile bir şablon oluşturmak hem düzenli hem de sürdürülebilir bir yaklaşımdır.


# Icon Composable ve Material Icons

## İkon Nedir?

Uygulamalarda metinle birlikte sıkça küçük semboller görülür: bir zil simgesi bildirim menüsünü, bir ev simgesi ana sayfayı, bir büyüteç simgesi arama ekranını temsil eder. Bu simgelere **ikon** adı verilir.

İkonlar, bir bilgiyi kelime kullanmadan aktarmanın en hızlı yoludur. Tıpkı trafik işaretleri gibi: "dur" yazmak yerine kırmızı sekizgen kullanılır, çünkü şekil metinden çok daha hızlı algılanır.

Jetpack Compose'da ikonları ekrana çizmek için `Icon` composable kullanılır.

---

## Material Icons Kütüphanesi

Google, Material Design sistemiyle birlikte yüzlerce hazır ikon tasarlamıştır. Bu ikonlar **Material Icons** kütüphanesi içinde gruplandırılmış olarak sunulmaktadır. Sıfırdan ikon tasarlamak yerine bu hazır ikonlar doğrudan kullanılabilir.

Material Icons kütüphanesi beş farklı stil içerir:

| Stil | Açıklama |
|---|---|
| `Icons.Filled` | Dolu, içi boyalı ikonlar |
| `Icons.Outlined` | Sadece dış çizgisi olan ikonlar |
| `Icons.Rounded` | Köşeleri yuvarlak ikonlar |
| `Icons.Sharp` | Köşeleri keskin ikonlar |
| `Icons.TwoTone` | İki renkli ikonlar |

Bunu bir kalem kutusuna benzetmek mümkündür. Kutu hazırdır; içinden istenen kalemi çekip kullanmak yeterlidir. Material Icons de aynı şekilde çalışır: kütüphane hazırdır, istenen ikon seçilip `Icon` composable'a verilir.

---

## Projeye Material Icons Ekleme

Material Icons kütüphanesi iki farklı boyutta sunulmaktadır. Temel ikonlar (`material-icons-core`) varsayılan olarak gelir; ancak genişletilmiş ikon seti (`material-icons-extended`) ayrıca projeye eklenmelidir.

`build.gradle.kts` (app düzeyi) dosyasına şu bağımlılık eklenir:

```kotlin
dependencies {
    implementation("androidx.compose.material:material-icons-extended")
}
```

Bu satır eklendikten sonra "Sync Now" tıklanır ve yüzlerce hazır ikona erişim sağlanır.

---

## Icon Composable'ın Temel Kullanımı

`Icon` composable üç temel parametre alır:

- **`imageVector`**: Hangi ikonun gösterileceği. `Icons.Filled.Home` gibi bir değer verilir.
- **`contentDescription`**: İkonun ne anlama geldiğini açıklayan metin. Ekran okuyucular (görme engelli kullanıcılar için) bu metni sesli okur. Eğer ikon yalnızca dekoratifse `null` verilebilir.
- **`tint`**: İkonun rengi. Verilmezse varsayılan tema rengi kullanılır.

```kotlin
// MainActivity.kt

package com.example.icontemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Favorite
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.Search
import androidx.compose.material3.Icon
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            TemelIkonlar()
        }
    }
}

@Composable
fun TemelIkonlar() {
    Column(modifier = Modifier.padding(16.dp)) {

        // Ev ikonu — varsayılan renk
        Icon(
            imageVector = Icons.Filled.Home,
            contentDescription = "Ana sayfa"
        )

        Spacer(modifier = Modifier.height(12.dp))

        // Büyüteç ikonu — mavi renk
        Icon(
            imageVector = Icons.Filled.Search,
            contentDescription = "Arama",
            tint = Color.Blue
        )

        Spacer(modifier = Modifier.height(12.dp))

        // Kalp ikonu — kırmızı renk
        Icon(
            imageVector = Icons.Filled.Favorite,
            contentDescription = "Favori",
            tint = Color.Red
        )
    }
}

@Preview(showBackground = true)
@Composable
fun TemelIkonlarOnizleme() {
    TemelIkonlar()
}
```

---

## İkon Boyutunu Ayarlamak

`Icon` composable'ın kendi boyut parametresi yoktur. Boyut ayarlamak için `Modifier.size()` kullanılır.

Bunu bir fotoğraf çerçevesi olarak düşünmek mümkündür. Fotoğrafın kendisi değişmez; çerçevenin büyüklüğü değiştirilerek fotoğrafın ne kadar alan kapladığı belirlenir. `Modifier.size()` da ikonun çerçevesini belirler.

```kotlin
// MainActivity.kt

package com.example.ikonboyut

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Star
import androidx.compose.material3.Icon
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            IkonBoyutlari()
        }
    }
}

@Composable
fun IkonBoyutlari() {
    Column(modifier = Modifier.padding(16.dp)) {

        // Küçük ikon
        Icon(
            imageVector = Icons.Filled.Star,
            contentDescription = "Küçük yıldız",
            tint = Color(0xFFFFC107),
            modifier = Modifier.size(24.dp)
        )

        Spacer(modifier = Modifier.height(12.dp))

        // Orta ikon
        Icon(
            imageVector = Icons.Filled.Star,
            contentDescription = "Orta yıldız",
            tint = Color(0xFFFFC107),
            modifier = Modifier.size(48.dp)
        )

        Spacer(modifier = Modifier.height(12.dp))

        // Büyük ikon
        Icon(
            imageVector = Icons.Filled.Star,
            contentDescription = "Büyük yıldız",
            tint = Color(0xFFFFC107),
            modifier = Modifier.size(80.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun IkonBoyutlariOnizleme() {
    IkonBoyutlari()
}
```

---

## İkon ve Metin Birlikte Kullanımı

Uygulamalarda ikonlar çoğunlukla tek başına değil, yanında bir metin ile birlikte kullanılır. Bu kombinasyon `Row` composable ile sağlanır; ikon ve metin yatay olarak yan yana dizilir.

```kotlin
// MainActivity.kt

package com.example.ikonvemetin

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Call
import androidx.compose.material.icons.filled.Email
import androidx.compose.material.icons.filled.LocationOn
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            IletisimBilgileri()
        }
    }
}

// İkon ve metni yan yana gösteren yardımcı composable
@Composable
fun IkonluSatir(ikon: androidx.compose.ui.graphics.vector.ImageVector, metin: String) {
    Row(
        verticalAlignment = Alignment.CenterVertically,
        modifier = Modifier.padding(vertical = 6.dp)
    ) {
        Icon(
            imageVector = ikon,
            contentDescription = null,
            tint = Color(0xFF1565C0),
            modifier = Modifier.size(20.dp)
        )
        Spacer(modifier = Modifier.width(8.dp))
        Text(text = metin, fontSize = 16.sp)
    }
}

@Composable
fun IletisimBilgileri() {
    Column(modifier = Modifier.padding(16.dp)) {
        IkonluSatir(ikon = Icons.Filled.Call, metin = "+90 555 123 45 67")
        IkonluSatir(ikon = Icons.Filled.Email, metin = "ornek@eposta.com")
        IkonluSatir(ikon = Icons.Filled.LocationOn, metin = "Ankara, Türkiye")
    }
}

@Preview(showBackground = true)
@Composable
fun IletisimBilgileriOnizleme() {
    IletisimBilgileri()
}
```

Bu örnekte `IkonluSatir` adında yardımcı bir composable oluşturulmuştur. Bu composable bir ikon ve bir metin alarak onları yan yana dizer. Aynı composable farklı ikon ve metinlerle üç kez çağrılmış, böylece kod tekrarından kaçınılmıştır.

---

## Özet

`Icon` composable, Material Icons kütüphanesindeki yüzlerce hazır ikonu ekrana çizmek için kullanılır. `imageVector` ile ikon seçilir, `tint` ile rengi belirlenir, `Modifier.size()` ile boyutu ayarlanır. İkonlar çoğunlukla `Row` içinde metinle birleştirilerek anlamlı arayüz satırları oluşturulur. Farklı icon stilleri (`Filled`, `Outlined`, `Rounded`) aynı ikonun farklı görünümlerde kullanılmasına olanak tanır.




# Spacer ve Divider Kullanımı

## Neden Boşluk ve Ayraç Gerekir?

Bir uygulamada bileşenler birbirinin hemen yanına ya da altına yerleştirildiğinde ekran kalabalık ve okunması güç bir hal alır. Tıpkı bir kitapta paragraflar arasında boşluk bırakılmaması gibi: tüm cümleler birbirine yapışık olsaydı okumak çok zorlaşırdı.

Jetpack Compose'da bileşenler arasına boşluk eklemek için **`Spacer`**, bileşenleri görsel olarak birbirinden ayırmak için ise **`Divider`** kullanılır.

---

## Spacer: Görünmez Boşluk

`Spacer`, ekranda hiçbir şey çizmez. Yalnızca belirtilen miktarda boş alan kaplar. `Modifier.height()` ile dikey, `Modifier.width()` ile yatay boşluk oluşturulur.

Bunu mobilyalar arasındaki boşluğa benzetmek mümkündür. İki koltuk arasına bir sehpa konulmak yerine aralarında sadece boş alan bırakılır. `Spacer` da tam olarak budur: görünmez ama alan kaplayan bir boşluk.

```kotlin
// MainActivity.kt

package com.example.spacertemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SpacerOrnegi()
        }
    }
}

@Composable
fun SpacerOrnegi() {
    Column(modifier = Modifier.padding(16.dp)) {

        Text(text = "Birinci bölüm", fontSize = 18.sp)

        // İki metin arasına 24 dp yüksekliğinde boşluk eklenir.
        Spacer(modifier = Modifier.height(24.dp))

        Text(text = "İkinci bölüm", fontSize = 18.sp)

        // Daha büyük bir boşluk
        Spacer(modifier = Modifier.height(48.dp))

        Text(text = "Üçüncü bölüm", fontSize = 18.sp)
    }
}

@Preview(showBackground = true)
@Composable
fun SpacerOrnegiOnizleme() {
    SpacerOrnegi()
}
```

`Spacer` olmadan bu üç metin birbirinin hemen altında, aralarında hiç nefes alanı olmadan görünürdü. `Spacer` eklenmesiyle her bölüm arasında görsel bir rahatlık oluşur.

---

## Spacer ile Yatay Boşluk

`Spacer`, `Row` içinde de kullanılabilir. Bu durumda `Modifier.width()` ile yatay boşluk eklenir.

```kotlin
// MainActivity.kt

package com.example.spaceryatay

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            YataySpacer()
        }
    }
}

@Composable
fun YataySpacer() {
    Row(modifier = Modifier.padding(16.dp)) {
        Text(text = "Sol", fontSize = 18.sp)

        // İki metin arasına 32 dp genişliğinde yatay boşluk eklenir.
        Spacer(modifier = Modifier.width(32.dp))

        Text(text = "Orta", fontSize = 18.sp)

        Spacer(modifier = Modifier.width(32.dp))

        Text(text = "Sağ", fontSize = 18.sp)
    }
}

@Preview(showBackground = true)
@Composable
fun YataySpacerOnizleme() {
    YataySpacer()
}
```

---

## Divider: Görünür Ayraç Çizgisi

`Divider`, bileşenler arasına ince bir yatay çizgi çizer. `Spacer`'dan farklı olarak görünür bir elemandır. Listelerde, ayarlarda ve kart içeriklerinde bölümleri birbirinden net şekilde ayırmak için kullanılır.

Bunu bir defterdeki yatay çizgilere benzetmek mümkündür. Çizgiler sayfayı bölümlere ayırır ve her bölümün nerede başlayıp nerede bittiğini netleştirir. `Divider` da ekranda aynı görevi üstlenir.

`HorizontalDivider` şu parametreleri alır:

| Parametre | Açıklama |
|---|---|
| `thickness` | Çizginin kalınlığı (`dp` cinsinden) |
| `color` | Çizginin rengi |
| `modifier` | Kenar boşlukları gibi ek düzenlemeler |

```kotlin
// MainActivity.kt

package com.example.dividertemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            DividerOrnegi()
        }
    }
}

@Composable
fun DividerOrnegi() {
    Column(modifier = Modifier.padding(16.dp)) {

        Text(text = "Kişisel Bilgiler", fontSize = 18.sp)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Ad: Ali Yılmaz", fontSize = 16.sp)
        Text(text = "Yaş: 28", fontSize = 16.sp)

        Spacer(modifier = Modifier.height(12.dp))

        // Varsayılan ince ayraç çizgisi
        HorizontalDivider()

        Spacer(modifier = Modifier.height(12.dp))

        Text(text = "İletişim Bilgileri", fontSize = 18.sp)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Telefon: +90 555 123 45 67", fontSize = 16.sp)
        Text(text = "E-posta: ali@eposta.com", fontSize = 16.sp)

        Spacer(modifier = Modifier.height(12.dp))

        // Kalın ve renkli ayraç çizgisi
        HorizontalDivider(
            thickness = 2.dp,
            color = Color(0xFF1565C0)
        )

        Spacer(modifier = Modifier.height(12.dp))

        Text(text = "Adres Bilgileri", fontSize = 18.sp)
        Spacer(modifier = Modifier.height(8.dp))
        Text(text = "Şehir: Ankara", fontSize = 16.sp)
    }
}

@Preview(showBackground = true)
@Composable
fun DividerOrnegiOnizleme() {
    DividerOrnegi()
}
```

---

## Spacer ve Divider Birlikte

Gerçek uygulamalarda `Spacer` ve `HorizontalDivider` çoğunlukla birlikte kullanılır. `HorizontalDivider` çizgiyi çizer, `Spacer` ise çizginin üstünde ve altında nefes alınacak boşluğu sağlar.

```kotlin
// MainActivity.kt

package com.example.spacerdividerbirlikte

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AyarlarEkrani()
        }
    }
}

// Bölüm başlığı ve içeriği için yardımcı composable
@Composable
fun AyarBolumu(baslik: String, icerik: String) {
    Spacer(modifier = Modifier.height(12.dp))
    Text(text = baslik, fontSize = 14.sp, fontWeight = FontWeight.Bold)
    Spacer(modifier = Modifier.height(4.dp))
    Text(text = icerik, fontSize = 16.sp)
    Spacer(modifier = Modifier.height(12.dp))
    HorizontalDivider()
}

@Composable
fun AyarlarEkrani() {
    Column(modifier = Modifier.padding(16.dp)) {
        Text(
            text = "Ayarlar",
            fontSize = 22.sp,
            fontWeight = FontWeight.Bold
        )

        AyarBolumu(baslik = "DİL", icerik = "Türkçe")
        AyarBolumu(baslik = "TEMA", icerik = "Sistem varsayılanı")
        AyarBolumu(baslik = "BİLDİRİMLER", icerik = "Açık")
    }
}

@Preview(showBackground = true)
@Composable
fun AyarlarEkraniOnizleme() {
    AyarlarEkrani()
}
```

Burada `AyarBolumu` composable her çağrıldığında üstte boşluk, ardından başlık, içerik, altta boşluk ve son olarak bir ayraç çizgisi oluşturur. Bu yapı, tekrar eden düzenin tek bir yerde tanımlanmasını ve kolayca yeniden kullanılmasını sağlar.

---

## Özet

`Spacer`, görünmez ama alan kaplayan bir boşluk bileşenidir; `Modifier.height()` ile dikey, `Modifier.width()` ile yatay boşluk oluşturur. `HorizontalDivider` ise bölümleri birbirinden ayıran görünür bir yatay çizgi çizer. Bu iki bileşen birlikte kullanıldığında ekran düzeni hem görsel açıdan dengeli hem de okunması kolay bir yapıya kavuşur.


# Alıştırma: Metin ve Resimlerle Basit Bir Ekran

## Alıştırmanın Amacı

Bu alıştırmada bu derse kadar öğrenilen tüm bileşenler — `Text`, `Image`, `Icon`, `Spacer` ve `HorizontalDivider` — tek bir ekranda bir araya getirilir. Hedef, gerçek bir uygulamada karşılaşılabilecek türde, sade ama düzenli bir profil kartı ekranı oluşturmaktır.

Bunu bir yapboza benzetmek mümkündür. Bu derse kadar her ders bir yapboz parçası tanıttı. Bu alıştırma ise o parçaları bir araya getirerek tamamlanmış bir resim ortaya çıkarır.

---

## Image Composable: Kısa Bir Hatırlatma

Ekrana resim çizmek için `Image` composable kullanılır. Resim kaynağı olarak `painterResource()` fonksiyonuna bir drawable kaynak ID'si verilir. `contentScale` parametresi ise resmin verilen alana nasıl sığdırılacağını belirler.

| `ContentScale` Değeri | Açıklama |
|---|---|
| `ContentScale.Crop` | Alanı tamamen doldurur, taşan kısmı keser |
| `ContentScale.Fit` | Resmin tamamı görünür, kenarlar boş kalabilir |
| `ContentScale.FillBounds` | Resmi uzatarak tam olarak alana sığdırır |

Bunu bir fotoğrafı çerçeveye yerleştirmeye benzetmek mümkündür. `Crop`, fotoğrafı çerçeveye tam sığdırır ama kenarlarını keser. `Fit`, fotoğrafın tamamını gösterir ama çerçevede boşluk kalabilir.

---

## Adım 1: Resmi Projeye Eklemek

Kod yazılmadan önce ekranda gösterilecek resmin projeye eklenmesi gerekir. Bunun için:

1. Android Studio'da sol panelde `res > drawable` klasörü sağ tıklanır.
2. "Show in Explorer" (ya da "Reveal in Finder") seçilir.
3. Kullanılacak resim dosyası bu klasöre kopyalanır.
4. Resim dosyasının adı yalnızca küçük harf, rakam ve alt çizgi (`_`) içermelidir. Örneğin: `profil_resmi.jpg`.

Bu alıştırmada resim adının `profil_resmi` olduğu varsayılmaktadır. Android Studio varsayılan olarak `ic_launcher_background` ve `ic_launcher_foreground` gibi birkaç drawable dosyasıyla gelir; resim eklenemediği durumlarda bu dosyalardan biri geçici olarak kullanılabilir.

---

## Adım 2: Ekran Tasarımını Planlamak

Kod yazmadan önce ekranın nasıl görüneceğini zihinsel olarak planlamak faydalıdır. Bu alıştırmada oluşturulacak ekran şu bileşenleri içerecektir:

```
[ Profil Resmi       ]  ← Image (yuvarlak, ortalı)
[ Ali Yılmaz         ]  ← Text (büyük, kalın, ortalı)
[ Android Geliştirici]  ← Text (küçük, gri, ortalı)

────────────────────── ← HorizontalDivider

[ 📍 Ankara, Türkiye ]  ← Icon + Text
[ ✉  ali@eposta.com  ]  ← Icon + Text
[ 📞 +90 555 123 4567]  ← Icon + Text

────────────────────── ← HorizontalDivider

[ Hakkında           ]  ← Text (başlık)
[ Açıklama metni...  ]  ← Text (paragraf)
```

---

## Adım 3: Kodu Yazmak

```kotlin
// MainActivity.kt

package com.example.profilekrani

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Call
import androidx.compose.material.icons.filled.Email
import androidx.compose.material.icons.filled.LocationOn
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ProfilEkrani()
        }
    }
}

// ── Yardımcı Composable: İkon + Metin Satırı ──────────────────
@Composable
fun IkonluBilgiSatiri(ikon: ImageVector, metin: String) {
    Row(
        verticalAlignment = Alignment.CenterVertically,
        modifier = Modifier.padding(vertical = 6.dp)
    ) {
        Icon(
            imageVector = ikon,
            contentDescription = null,
            tint = Color(0xFF1565C0),
            modifier = Modifier.size(20.dp)
        )
        Spacer(modifier = Modifier.width(10.dp))
        Text(
            text = metin,
            fontSize = 15.sp,
            color = Color(0xFF424242)
        )
    }
}

// ── Ana Ekran Composable ───────────────────────────────────────
@Composable
fun ProfilEkrani() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(24.dp)
            // İçerik ekrana sığmazsa kaydırılabilir olması için
            .verticalScroll(rememberScrollState()),
        horizontalAlignment = Alignment.CenterHorizontally
    ) {

        Spacer(modifier = Modifier.height(16.dp))

        // ── Profil Resmi ──────────────────────────────────────
        // clip(CircleShape) resmi yuvarlak yapar.
        // Tıpkı yuvarlak bir çerçeveye fotoğraf koymak gibi:
        // çerçeve yuvarlaktır, içindeki resim de yuvarlak görünür.
        Image(
            painter = painterResource(id = android.R.drawable.sym_def_app_icon),
            contentDescription = "Profil resmi",
            contentScale = ContentScale.Crop,
            modifier = Modifier
                .size(100.dp)
                .clip(CircleShape)
        )

        Spacer(modifier = Modifier.height(16.dp))

        // ── İsim ──────────────────────────────────────────────
        Text(
            text = "Ali Yılmaz",
            fontSize = 24.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF1A237E)
        )

        Spacer(modifier = Modifier.height(4.dp))

        // ── Unvan ─────────────────────────────────────────────
        Text(
            text = "Android Geliştirici",
            fontSize = 15.sp,
            color = Color(0xFF757575)
        )

        Spacer(modifier = Modifier.height(20.dp))
        HorizontalDivider()
        Spacer(modifier = Modifier.height(16.dp))

        // ── İletişim Bilgileri ────────────────────────────────
        // fillMaxWidth ile satırlar ekranın tüm genişliğini kullanır.
        Column(modifier = Modifier.fillMaxWidth()) {
            IkonluBilgiSatiri(
                ikon = Icons.Filled.LocationOn,
                metin = "Ankara, Türkiye"
            )
            IkonluBilgiSatiri(
                ikon = Icons.Filled.Email,
                metin = "ali@eposta.com"
            )
            IkonluBilgiSatiri(
                ikon = Icons.Filled.Call,
                metin = "+90 555 123 45 67"
            )
        }

        Spacer(modifier = Modifier.height(16.dp))
        HorizontalDivider()
        Spacer(modifier = Modifier.height(16.dp))

        // ── Hakkında Bölümü ───────────────────────────────────
        Text(
            text = "Hakkında",
            fontSize = 18.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF1A237E),
            modifier = Modifier.fillMaxWidth()
        )

        Spacer(modifier = Modifier.height(8.dp))

        Text(
            text = "Beş yıldır Android uygulama geliştirme alanında " +
                   "çalışmaktadır. Jetpack Compose, Kotlin ve " +
                   "Material Design konularında deneyim sahibidir. " +
                   "Açık kaynak projelere katkı sağlamaktan keyif alır.",
            fontSize = 15.sp,
            color = Color(0xFF424242),
            lineHeight = 22.sp,
            textAlign = TextAlign.Start,
            modifier = Modifier.fillMaxWidth()
        )

        Spacer(modifier = Modifier.height(24.dp))
    }
}

// ── Önizleme ──────────────────────────────────────────────────
@Preview(showBackground = true)
@Composable
fun ProfilEkraniOnizleme() {
    ProfilEkrani()
}
```

---

## Kodun Önemli Noktaları

**`clip(CircleShape)`:** `Modifier.clip()`, bir bileşenin köşelerini belirli bir şekle göre keser. `CircleShape` verildiğinde bileşen daire biçiminde kırpılır. Bu, profil resimlerini yuvarlak göstermenin en yaygın yoludur.

**`verticalScroll(rememberScrollState())`:** Ekran içeriği telefonun yüksekliğinden fazla olduğunda içerik ekranın dışına taşar ve görünmez hale gelir. `verticalScroll` modifier'ı bu durumda içeriğin yukarı-aşağı kaydırılabilmesini sağlar. `rememberScrollState()` ise kaydırma pozisyonunu hatırlar.

**`lineHeight`:** `Text` composable'da satırlar arası mesafeyi belirler. Uzun paragraf metinlerinde `lineHeight` artırılarak metin daha rahat okunur hale getirilir.

**`android.R.drawable.sym_def_app_icon`:** Bu, Android'in kendi kaynaklarından gelen varsayılan bir ikonudur. Gerçek bir projede bu değer, `res/drawable` klasörüne eklenen resmin adıyla (`R.drawable.profil_resmi` gibi) değiştirilir.

---

## Özet

Bu alıştırmada `Image`, `Text`, `Icon`, `Spacer` ve `HorizontalDivider` bileşenleri `Column` ve `Row` düzenleri içinde bir araya getirilmiş; `clip`, `verticalScroll` ve `lineHeight` gibi ek özellikler kullanılarak ekrana gerçek bir uygulamayı andıran düzenli bir profil sayfası çizilmiştir. Tüm bu bileşenler artık bir arada kullanılabilecek düzeyde kavranmış olmaktadır.



# Column: Dikey Yerleşim, Temel Kullanım

## Column Nedir?

Birden fazla bileşeni ekrana yerleştirirken bunların nereye gideceğini belirlemek gerekir. Bileşenler herhangi bir düzen bileşeni kullanılmadan yazıldığında hepsi üst üste yığılır ve yalnızca en üstteki görünür.

`Column`, içine yerleştirilen bileşenleri **yukarıdan aşağıya, alt alta** dizer. Türkçe karşılığıyla "sütun" anlamına gelir; tıpkı bir binanın sütunundaki taşların üst üste dizilmesi gibi her bileşen bir öncekinin hemen altına yerleşir.

Bunu bir alışveriş listesine benzetmek mümkündür. Listedeki her madde bir öncekinin altına yazılır; hiçbiri yan yana gelmez. `Column` da bileşenleri tam olarak bu şekilde, sırayla alt alta dizer.

---

## Column'un Temel Kullanımı

`Column` bir kapsayıcıdır. Süslü parantezleri içine yazılan tüm bileşenler dikey olarak dizilir.

```kotlin
// MainActivity.kt

package com.example.columntemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            IlkColumn()
        }
    }
}

@Composable
fun IlkColumn() {
    Column(modifier = Modifier.padding(16.dp)) {
        Text(text = "Birinci satır", fontSize = 18.sp)
        Text(text = "İkinci satır", fontSize = 18.sp)
        Text(text = "Üçüncü satır", fontSize = 18.sp)
        Text(text = "Dördüncü satır", fontSize = 18.sp)
    }
}

@Preview(showBackground = true)
@Composable
fun IlkColumnOnizleme() {
    IlkColumn()
}
```

Dört `Text` bileşeni `Column` içine yazılmıştır ve her biri bir öncekinin hemen altında görünür. `Column` olmadan bu dört metin aynı konuma yığılırdı.

---

## Column'un Modifier ile Kullanımı

`Column`, tıpkı diğer bileşenler gibi `Modifier` alır. En sık kullanılan modifier'lar şunlardır:

**`fillMaxSize()`:** `Column`'un ekranın tamamını kaplamasını sağlar. Bunu bir kağıdın tüm sayfayı doldurmasına benzetmek mümkündür; kağıt ne kadar büyükse o kadar yer kaplar.

**`fillMaxWidth()`:** `Column`'un yalnızca genişlik olarak ekranı tamamen kaplamasını, yükseklik olarak ise içeriği kadar yer tutmasını sağlar.

**`padding()`:** `Column`'un kenarları ile içindeki bileşenler arasına boşluk ekler.

```kotlin
// MainActivity.kt

package com.example.columnmodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            DoluColumn()
        }
    }
}

@Composable
fun DoluColumn() {
    // fillMaxSize: Column tüm ekranı kaplar.
    // padding: kenarlardan 24 dp boşluk bırakır.
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(24.dp)
    ) {
        Text(text = "Başlık", fontSize = 22.sp)
        Text(text = "Alt başlık", fontSize = 16.sp)
        Text(text = "Açıklama metni buraya gelir.", fontSize = 14.sp)
    }
}

@Preview(showBackground = true)
@Composable
fun DoluColumnOnizleme() {
    DoluColumn()
}
```

---

## verticalArrangement: Bileşenler Arası Boşluk

Varsayılan olarak `Column` içindeki bileşenler birbirine yapışık şekilde dizilir. `verticalArrangement` parametresi bileşenlerin dikey eksende nasıl dağıtılacağını belirler.

En sık kullanılan değerler şunlardır:

| Değer | Açıklama |
|---|---|
| `Arrangement.Top` | Bileşenler üstten başlar (varsayılan) |
| `Arrangement.Bottom` | Bileşenler alta hizalanır |
| `Arrangement.Center` | Bileşenler ortada toplanır |
| `Arrangement.SpaceBetween` | İlk ve son bileşen kenarlarda, aralar eşit boşluklu |
| `Arrangement.SpaceEvenly` | Tüm boşluklar eşit (kenarlar dahil) |
| `Arrangement.SpaceAround` | Kenar boşlukları, ara boşlukların yarısı kadar |
| `Arrangement.spacedBy(x.dp)` | Bileşenler arasına sabit boşluk |

Bunu bir raf düzenine benzetmek mümkündür. Rafa birkaç kitap konulduğunda kitaplar üst üste yığılmak yerine aralarında eşit boşluk bırakılarak dizilir. `verticalArrangement` bu boşluk düzenini belirler.

```kotlin
// MainActivity.kt

package com.example.columnarrangement

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AranjmanOrnegi()
        }
    }
}

@Composable
fun AranjmanOrnegi() {
    // SpaceEvenly: tüm boşluklar eşit dağıtılır.
    // fillMaxSize ile Column tüm ekranı kaplar;
    // böylece bileşenler ekrana yayılır.
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.SpaceEvenly
    ) {
        MetinKutusu(metin = "Birinci Bölüm", renk = Color(0xFFE3F2FD))
        MetinKutusu(metin = "İkinci Bölüm", renk = Color(0xFFE8F5E9))
        MetinKutusu(metin = "Üçüncü Bölüm", renk = Color(0xFFFFF8E1))
        MetinKutusu(metin = "Dördüncü Bölüm", renk = Color(0xFFFCE4EC))
    }
}

// Görsel fark yaratmak için renkli arka planlı metin kutusu
@Composable
fun MetinKutusu(metin: String, renk: Color) {
    Text(
        text = metin,
        fontSize = 16.sp,
        fontWeight = FontWeight.Medium,
        textAlign = TextAlign.Center,
        modifier = Modifier
            .fillMaxWidth()
            .background(renk)
            .padding(12.dp)
    )
}

@Preview(showBackground = true)
@Composable
fun AranjmanOrnegiOnizleme() {
    AranjmanOrnegi()
}
```

---

## horizontalAlignment: Yatay Hizalama

`Column` içindeki bileşenler varsayılan olarak sola hizalanır. `horizontalAlignment` parametresiyle bu hizalama değiştirilebilir.

| Değer | Açıklama |
|---|---|
| `Alignment.Start` | Sola hizala (varsayılan) |
| `Alignment.CenterHorizontally` | Ortaya hizala |
| `Alignment.End` | Sağa hizala |

```kotlin
// MainActivity.kt

package com.example.columnhizalama

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            OrtalanmisColumn()
        }
    }
}

@Composable
fun OrtalanmisColumn() {
    // Hem dikey hem yatay olarak tam ortaya hizalanmış Column
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.Center,
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(
            text = "Hoş Geldiniz",
            fontSize = 28.sp,
            fontWeight = FontWeight.Bold
        )
        Text(
            text = "Bu uygulama Column hizalamasını gösterir.",
            fontSize = 15.sp
        )
        Text(
            text = "Her şey tam ortada.",
            fontSize = 15.sp
        )
    }
}

@Preview(showBackground = true)
@Composable
fun OrtalanmisColumnOnizleme() {
    OrtalanmisColumn()
}
```

`verticalArrangement = Arrangement.Center` bileşenleri dikey eksende ortalar, `horizontalAlignment = Alignment.CenterHorizontally` ise yatay eksende ortalar. Bu kombinasyon, giriş ekranları ve boş durum ekranları gibi yerlerde sıkça kullanılır.

---

## Özet

`Column`, bileşenleri yukarıdan aşağıya dikey olarak dizen temel bir düzen bileşenidir. `Modifier` ile boyutu ve boşlukları, `verticalArrangement` ile bileşenler arası dikey dağılım, `horizontalAlignment` ile yatay hizalama ayarlanır. Tüm bu parametreler birlikte kullanıldığında bileşenler ekran üzerinde tam istenilen konuma yerleştirilebilir.


# Row: Yatay Yerleşim, Temel Kullanım

## Row Nedir?

Bir önceki derste `Column`'un bileşenleri yukarıdan aşağıya dizdiği ele alınmıştı. `Row` ise bunun tam tersi yönde çalışır: içine yerleştirilen bileşenleri **soldan sağa, yan yana** dizer.

Türkçe karşılığıyla "satır" anlamına gelir. Bunu bir okul sıralarına benzetmek mümkündür. Sıradaki öğrenciler yan yana otururlar, üst üste değil. `Row` da bileşenleri tam olarak bu şekilde, yatay bir çizgi üzerinde yan yana dizer.

---

## Row'un Temel Kullanımı

`Column`'da olduğu gibi `Row` da bir kapsayıcıdır. Süslü parantezlerinin içine yazılan tüm bileşenler yatay olarak sıralanır.

```kotlin
// MainActivity.kt

package com.example.rowtemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            IlkRow()
        }
    }
}

@Composable
fun IlkRow() {
    Row(modifier = Modifier.padding(16.dp)) {
        Text(text = "Birinci", fontSize = 18.sp)
        Text(text = "İkinci", fontSize = 18.sp)
        Text(text = "Üçüncü", fontSize = 18.sp)
    }
}

@Preview(showBackground = true)
@Composable
fun IlkRowOnizleme() {
    IlkRow()
}
```

Üç `Text` bileşeni `Row` içine yazılmıştır ve hepsi yan yana görünür. Ancak aralarında hiç boşluk olmadığı dikkat çeker; metinler birbirine yapışık durur. Boşluk eklemek için `Spacer` ya da `horizontalArrangement` kullanılır.

---

## horizontalArrangement: Yatay Dağılım

`Column`'da `verticalArrangement` nasıl bileşenlerin dikey dağılımını belirliyorsa, `Row`'da da `horizontalArrangement` bileşenlerin **yatay dağılımını** belirler.

| Değer | Açıklama |
|---|---|
| `Arrangement.Start` | Bileşenler soldan başlar (varsayılan) |
| `Arrangement.End` | Bileşenler sağa hizalanır |
| `Arrangement.Center` | Bileşenler ortada toplanır |
| `Arrangement.SpaceBetween` | İlk ve son kenarda, aralar eşit boşluklu |
| `Arrangement.SpaceEvenly` | Tüm boşluklar eşit (kenarlar dahil) |
| `Arrangement.SpaceAround` | Kenar boşlukları, ara boşlukların yarısı kadar |
| `Arrangement.spacedBy(x.dp)` | Bileşenler arasına sabit boşluk |

Bunu bir çamaşır ipine asılan çamaşırlara benzetmek mümkündür. `SpaceBetween` çamaşırları ipin tam iki ucuna yerleştirip aradaki boşluğu eşit böler. `SpaceEvenly` ise uçlarla aradaki tüm boşlukları eşit tutar.

```kotlin
// MainActivity.kt

package com.example.rowarrangement

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            YatayAranjmanlar()
        }
    }
}

// Görsel karşılaştırma için renkli kutu
@Composable
fun RenkliKutu(metin: String, renk: Color) {
    Text(
        text = metin,
        fontSize = 13.sp,
        fontWeight = FontWeight.Bold,
        color = Color.White,
        modifier = Modifier
            .background(renk)
            .padding(horizontal = 10.dp, vertical = 6.dp)
    )
}

@Composable
fun YatayAranjmanlar() {
    Column(modifier = Modifier.padding(16.dp)) {

        Text(text = "SpaceBetween", fontSize = 13.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceBetween
        ) {
            RenkliKutu("A", Color(0xFF1565C0))
            RenkliKutu("B", Color(0xFF1565C0))
            RenkliKutu("C", Color(0xFF1565C0))
        }

        Spacer(modifier = Modifier.height(16.dp))

        Text(text = "SpaceEvenly", fontSize = 13.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceEvenly
        ) {
            RenkliKutu("A", Color(0xFF2E7D32))
            RenkliKutu("B", Color(0xFF2E7D32))
            RenkliKutu("C", Color(0xFF2E7D32))
        }

        Spacer(modifier = Modifier.height(16.dp))

        Text(text = "Center", fontSize = 13.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.Center
        ) {
            RenkliKutu("A", Color(0xFFC62828))
            RenkliKutu("B", Color(0xFFC62828))
            RenkliKutu("C", Color(0xFFC62828))
        }

        Spacer(modifier = Modifier.height(16.dp))

        Text(text = "spacedBy(16.dp)", fontSize = 13.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.spacedBy(16.dp)
        ) {
            RenkliKutu("A", Color(0xFF6A1B9A))
            RenkliKutu("B", Color(0xFF6A1B9A))
            RenkliKutu("C", Color(0xFF6A1B9A))
        }
    }
}

@Preview(showBackground = true)
@Composable
fun YatayAranjmanlarOnizleme() {
    YatayAranjmanlar()
}
```

---

## verticalAlignment: Dikey Hizalama

`Row` içindeki bileşenler farklı yüksekliklere sahip olabilir. `verticalAlignment` parametresi bu bileşenlerin dikey eksende nasıl hizalanacağını belirler.

| Değer | Açıklama |
|---|---|
| `Alignment.Top` | Üste hizala (varsayılan) |
| `Alignment.CenterVertically` | Ortaya hizala |
| `Alignment.Bottom` | Alta hizala |

Bunu farklı boyutlardaki kutular içeren bir raf olarak düşünmek mümkündür. Büyük kutu ile küçük kutu rafa konulduğunda küçük kutunun üste mi, ortaya mı yoksa alta mı hizalanacağına `verticalAlignment` karar verir.

```kotlin
// MainActivity.kt

package com.example.rowverticalalignment

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            DikeyHizalama()
        }
    }
}

@Composable
fun DikeyHizalama() {
    // Farklı fontSize değerleri, bileşenlerin farklı yükseklikte
    // olmasını sağlar. verticalAlignment bu farkı dengeler.
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp),
        horizontalArrangement = Arrangement.spacedBy(12.dp),
        verticalAlignment = Alignment.CenterVertically
    ) {
        Text(
            text = "Büyük",
            fontSize = 28.sp,
            fontWeight = FontWeight.Bold
        )
        Text(
            text = "Orta",
            fontSize = 18.sp
        )
        Text(
            text = "Küçük",
            fontSize = 12.sp
        )
    }
}

@Preview(showBackground = true)
@Composable
fun DikeyHizalamaOnizleme() {
    DikeyHizalama()
}
```

`verticalAlignment = Alignment.CenterVertically` olmadan büyük metin üstte, küçük metin de kendi üst kenarında hizalanır ve düzensiz görünür. Bu parametre eklenerek üç farklı boyuttaki metin dikey olarak ortaya hizalanır.

---

## Row ile Gerçek Hayat Örneği: Kullanıcı Satırı

`Row`, uygulamalarda en sık kişi listelerinde, ayar satırlarında ve navigasyon çubuklarında karşılaşılan bir yapıdır. Aşağıdaki örnek, sol tarafta ikon, ortada isim ve açıklama, sağda ise ek bir bilgi içeren tipik bir liste satırını göstermektedir.

```kotlin
// MainActivity.kt

package com.example.rowkullanici

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.AccountCircle
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            KullaniciListesi()
        }
    }
}

// Tek bir kullanıcı satırı
@Composable
fun KullaniciSatiri(isim: String, meslek: String, konum: String) {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .padding(vertical = 10.dp),
        verticalAlignment = Alignment.CenterVertically,
        horizontalArrangement = Arrangement.SpaceBetween
    ) {
        // Sol taraf: ikon
        Icon(
            imageVector = Icons.Filled.AccountCircle,
            contentDescription = null,
            tint = Color(0xFF1565C0),
            modifier = Modifier.size(40.dp)
        )

        // Orta: isim ve meslek (Column ile alt alta)
        Column(
            modifier = Modifier
                .weight(1f)
                .padding(horizontal = 12.dp)
        ) {
            Text(
                text = isim,
                fontSize = 16.sp,
                fontWeight = FontWeight.Bold
            )
            Text(
                text = meslek,
                fontSize = 13.sp,
                color = Color.Gray
            )
        }

        // Sağ taraf: konum bilgisi
        Text(
            text = konum,
            fontSize = 13.sp,
            color = Color(0xFF1565C0)
        )
    }
}

@Composable
fun KullaniciListesi() {
    Column(modifier = Modifier.padding(horizontal = 16.dp)) {
        Text(
            text = "Ekip Üyeleri",
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold,
            modifier = Modifier.padding(vertical = 12.dp)
        )
        HorizontalDivider()
        KullaniciSatiri("Ali Yılmaz", "Android Geliştirici", "Ankara")
        HorizontalDivider()
        KullaniciSatiri("Ayşe Kara", "UI Tasarımcı", "İstanbul")
        HorizontalDivider()
        KullaniciSatiri("Mehmet Çelik", "Backend Geliştirici", "İzmir")
        HorizontalDivider()
    }
}

@Preview(showBackground = true)
@Composable
fun KullaniciListesiOnizleme() {
    KullaniciListesi()
}
```

Bu örnekte `Row` ve `Column` birlikte kullanılmıştır. Her satır bir `Row`'dur; satırın ortasındaki isim ve meslek bilgisi ise kendi içinde bir `Column` ile alt alta dizilmiştir. `weight(1f)` parametresi orta sütunun kalan tüm genişliği kaplamasını sağlar; böylece sağdaki konum metni her zaman sağ kenara yaslanır.

---

## Özet

`Row`, bileşenleri soldan sağa yatay olarak dizen temel bir düzen bileşenidir. `horizontalArrangement` ile bileşenlerin yatay dağılımı, `verticalAlignment` ile dikey hizalama ayarlanır. `Column` ile birlikte kullanıldığında karmaşık ve gerçekçi arayüz düzenleri oluşturmak mümkün hale gelir.


# Layout'ları İç İçe Kullanma

## Neden İç İçe Layout Gerekir?

`Column` bileşenleri alt alta dizer, `Row` ise yan yana. Ancak gerçek uygulamalardaki ekranlar yalnızca "hepsi alt alta" ya da "hepsi yan yana" gibi tek düze yapılardan oluşmaz. Bir ekranda hem alt alta dizilmiş bölümler hem de bu bölümlerin içinde yan yana dizilmiş elemanlar bulunabilir.

Bu tür karmaşık düzenler oluşturmak için `Column`, `Row` ve `Box` bileşenleri **birbirinin içine yerleştirilerek** kullanılır. Buna **iç içe layout** adı verilir.

Bunu bir dolap düzenine benzetmek mümkündür. Dolabın rafları alt altadır (`Column`). Her rafın üzerinde ise eşyalar yan yanadır (`Row`). Raf, `Column`'dur; rafın üzerindeki düzen ise `Row`'dur. Biri diğerinin içindedir.

---

## Temel İç İçe Kullanım: Column İçinde Row

En yaygın iç içe kullanım biçimi, bir `Column` içine birden fazla `Row` yerleştirmektir. Bu sayede her satır kendi içinde yatay bir düzen oluştururken satırların kendisi dikey olarak sıralanır.

```kotlin
// MainActivity.kt

package com.example.icicenlayout

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            UrunKarti()
        }
    }
}

@Composable
fun UrunKarti() {
    // Dış Column: tüm içerik alt alta dizilir
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp)
    ) {
        Text(
            text = "Kablosuz Kulaklık",
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold
        )

        Spacer(modifier = Modifier.height(8.dp))

        // İç Row: fiyat ve puan yan yana dizilir
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceBetween,
            verticalAlignment = Alignment.CenterVertically
        ) {
            Text(
                text = "₺ 1.299",
                fontSize = 18.sp,
                fontWeight = FontWeight.Bold
            )
            Text(
                text = "⭐ 4.7",
                fontSize = 16.sp
            )
        }

        Spacer(modifier = Modifier.height(8.dp))

        Text(
            text = "40 saate kadar pil ömrü, aktif gürültü engelleme.",
            fontSize = 14.sp
        )
    }
}

@Preview(showBackground = true)
@Composable
fun UrunKartiOnizleme() {
    UrunKarti()
}
```

Burada dış `Column` tüm içeriği alt alta dizer. İçerideki `Row` ise yalnızca fiyat ve puan bilgisini yan yana hizalamak için kullanılmıştır. İkisi birlikte çalışarak tek bir layout ile elde edilemeyecek bir düzen oluşturur.

---

## Row İçinde Column Kullanımı

Bazı durumlarda dış yapı yatay, iç yapı ise dikey olur. Bunu bir gazete sayfasına benzetmek mümkündür: sütunlar yan yanadır (`Row`), ancak her sütunun içindeki paragraflar alt altadır (`Column`).

```kotlin
// MainActivity.kt

package com.example.rowicindecol

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            IstatistikSatiri()
        }
    }
}

// Tek bir istatistik bloğu: sayı üstte, etiket altta
@Composable
fun IstatistikBlok(sayi: String, etiket: String) {
    Column(horizontalAlignment = androidx.compose.ui.Alignment.CenterHorizontally) {
        Text(
            text = sayi,
            fontSize = 22.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF1565C0)
        )
        Text(
            text = etiket,
            fontSize = 13.sp,
            color = Color.Gray
        )
    }
}

@Composable
fun IstatistikSatiri() {
    // Dış Row: üç blok yan yana dizilir
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp),
        horizontalArrangement = Arrangement.SpaceEvenly
    ) {
        // Her blok kendi içinde bir Column'dur
        IstatistikBlok(sayi = "128", etiket = "Gönderi")
        IstatistikBlok(sayi = "4.2K", etiket = "Takipçi")
        IstatistikBlok(sayi = "312", etiket = "Takip")
    }
}

@Preview(showBackground = true)
@Composable
fun IstatistikSatiriOnizleme() {
    IstatistikSatiri()
}
```

`IstatistikBlok`, sayıyı ve etiketi alt alta dizen bir `Column`'dur. Üç adet `IstatistikBlok` ise bir `Row` içinde yan yana dizilmiştir. Sonuçta sosyal medya profil sayfalarında sıkça görülen istatistik satırı elde edilir.

---

## Dikkat Edilmesi Gereken Nokta: Aşırı İç İçe Kullanım

İç içe layout kullanımı güçlü bir araçtır; ancak gereğinden fazla iç içe yerleştirme kodu karmaşık ve okunması güç hale getirir. Bu duruma yazılımcılar arasında "layout karmaşası" (deeply nested layouts) adı verilir.

Bunu içinde oda olan bir odanın içinde başka bir oda olan bir yapıya benzetmek mümkündür. Bir noktadan sonra hangi odada olunduğunu bulmak güçleşir.

Bu sorunu çözmenin en etkili yolu, iç içe yapının belirli bölümlerini **ayrı @Composable fonksiyonlara** taşımaktır. Önceki örnekte `IstatistikBlok` fonksiyonu tam olarak bu amaca hizmet etmiştir: `Row` içindeki `Column` yapısı ayrı bir fonksiyon olarak tanımlanmış ve kod hem okunabilir hem de yeniden kullanılabilir hale getirilmiştir.

---

## Özet

`Column`, `Row` ve `Box` layout bileşenleri birbirinin içine yerleştirilerek karmaşık ekran düzenleri oluşturulabilir. En yaygın kullanım biçimleri `Column` içinde `Row` ve `Row` içinde `Column` kombinasyonlarıdır. Aşırı iç içe kullanımdan kaçınmak için tekrar eden yapılar ayrı @Composable fonksiyonlara taşınmalıdır; bu yaklaşım kodu hem düzenli hem de yeniden kullanılabilir kılar.



# Alıştırma: Basit Bir Kart Düzeni

## Alıştırmanın Amacı

Bu alıştırmada bu derse kadar öğrenilen `Column`, `Row` ve `Box` layout bileşenleri ile `Text`, `Icon`, `Spacer` ve `HorizontalDivider` bileşenleri bir arada kullanılarak gerçek bir uygulamada görülebilecek sade bir kart düzeni oluşturulur.

Bunu bir pasta tarifi olarak düşünmek mümkündür. Bu derse kadar öğrenilen her bileşen ayrı bir malzemeydi. Bu alıştırma ise tüm malzemeleri bir araya getirerek ortaya tam bir ürün çıkaran tarif adımıdır.

---

## Kartın Planlanması

Kod yazmadan önce kartın yapısını zihinsel olarak planlamak, hangi layout bileşeninin nerede kullanılacağını netleştirir:

```
┌─────────────────────────────────┐
│  🎵  Playlist Adı               │  ← Row (ikon + Column)
│      Sanatçı Adı                │
├─────────────────────────────────┤
│  12 şarkı          48 dk        │  ← Row (SpaceBetween)
├─────────────────────────────────┤
│  En çok dinlenenler:            │  ← Text
│  • Şarkı 1                      │  ← Column
│  • Şarkı 2                      │
│  • Şarkı 3                      │
└─────────────────────────────────┘
```

Kartın tamamı bir dış `Column` içinde yer alır. Üst kısım ikon ve metin bilgisini yan yana gösteren bir `Row` içerir. Orta kısım iki bilgiyi zıt kenarlara yerleştiren bir `Row`'dur. Alt kısım ise şarkı listesini alt alta dizen bir `Column`'dur.

---

## Kodu Yazmak

```kotlin
// MainActivity.kt

package com.example.kartalıştırma

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.MusicNote
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.shadow
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            // Kartı ekranın kenarlarından biraz içeride göstermek için
            // dış bir Column ve padding kullanılır.
            Column(
                modifier = Modifier
                    .padding(20.dp)
            ) {
                PlaylistKarti()
            }
        }
    }
}

// ── Yardımcı Composable: Tek Şarkı Satırı ─────────────────────
@Composable
fun SarkirSatiri(sarki: String) {
    Text(
        text = "•  $sarki",
        fontSize = 14.sp,
        color = Color(0xFF424242),
        modifier = Modifier.padding(vertical = 3.dp)
    )
}

// ── Ana Kart Composable ────────────────────────────────────────
@Composable
fun PlaylistKarti() {
    // Kartın arka planı, köşe yuvarlama ve gölge efekti
    // shadow: kartı sayfadan hafifçe yukarı kaldırılmış gibi gösterir.
    // Bunu masanın üzerindeki bir kağıdın gölgesine benzetmek mümkündür.
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .shadow(
                elevation = 6.dp,
                shape = RoundedCornerShape(16.dp)
            )
            .background(
                color = Color.White,
                shape = RoundedCornerShape(16.dp)
            )
            .padding(16.dp)
    ) {

        // ── Üst Kısım: İkon + Playlist Bilgisi ────────────────
        Row(
            verticalAlignment = Alignment.CenterVertically
        ) {
            // Müzik ikonu, renkli bir kare arka plan üzerinde durur.
            // Bu küçük tasarım detayı kartı daha canlı gösterir.
            Column(
                modifier = Modifier
                    .background(
                        color = Color(0xFF1565C0),
                        shape = RoundedCornerShape(10.dp)
                    )
                    .padding(10.dp),
                horizontalAlignment = Alignment.CenterHorizontally
            ) {
                Icon(
                    imageVector = Icons.Filled.MusicNote,
                    contentDescription = null,
                    tint = Color.White,
                    modifier = Modifier.size(28.dp)
                )
            }

            Spacer(modifier = Modifier.width(14.dp))

            // Playlist adı ve sanatçı adı alt alta
            Column {
                Text(
                    text = "Sabah Enerjisi",
                    fontSize = 18.sp,
                    fontWeight = FontWeight.Bold,
                    color = Color(0xFF1A237E)
                )
                Text(
                    text = "Çeşitli Sanatçılar",
                    fontSize = 13.sp,
                    color = Color.Gray
                )
            }
        }

        Spacer(modifier = Modifier.height(14.dp))
        HorizontalDivider(color = Color(0xFFEEEEEE))
        Spacer(modifier = Modifier.height(12.dp))

        // ── Orta Kısım: Şarkı Sayısı ve Süre ─────────────────
        // SpaceBetween: şarkı sayısı sola, süre sağa yaslanır.
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceBetween
        ) {
            Text(
                text = "12 şarkı",
                fontSize = 13.sp,
                color = Color(0xFF1565C0),
                fontWeight = FontWeight.Medium
            )
            Text(
                text = "48 dk",
                fontSize = 13.sp,
                color = Color(0xFF1565C0),
                fontWeight = FontWeight.Medium
            )
        }

        Spacer(modifier = Modifier.height(12.dp))
        HorizontalDivider(color = Color(0xFFEEEEEE))
        Spacer(modifier = Modifier.height(12.dp))

        // ── Alt Kısım: Şarkı Listesi ───────────────────────────
        Text(
            text = "En çok dinlenenler",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF424242)
        )

        Spacer(modifier = Modifier.height(6.dp))

        // Her şarkı ayrı bir satır composable ile gösterilir.
        // Aynı composable farklı verilerle tekrar tekrar çağrılır.
        SarkirSatiri("Güne Başlarken")
        SarkirSatiri("Ritim ve Hareket")
        SarkirSatiri("Sabah Işığı")
    }
}

@Preview(showBackground = true)
@Composable
fun PlaylistKartiOnizleme() {
    Column(modifier = Modifier.padding(20.dp)) {
        PlaylistKarti()
    }
}
```

---

## Kodun Önemli Noktaları

**`shadow()` ve `RoundedCornerShape`:** `shadow` modifier'ı kartın altına hafif bir gölge ekler; bu, kartın sayfadan ayrılmış gibi görünmesini sağlar. `RoundedCornerShape(16.dp)` ise kartın köşelerini 16 dp yarıçapında yuvarlar. Bu iki özellik birlikte kullanıldığında kart düz bir dikdörtgen yerine modern ve yumuşak bir görünüm kazanır. `shadow` ve `background` modifier'larına aynı `shape` değeri verilmesi önemlidir; aksi takdirde gölge ile arka plan farklı biçimlerde görünür.

**İkon Arka Planı için `Column`:** İkonun arkasındaki mavi kare aslında `background` modifier'ı uygulanmış bir `Column`'dur. `RoundedCornerShape` ile köşeleri yuvarlanmış, `padding` ile ikon çevresine nefes alanı bırakılmıştır. Bu yaklaşım, `Box` kullanılmadan da ikonlara arka plan eklemenin pratik bir yoludur.

**`SarkirSatiri` yardımcı composable'ı:** Şarkı satırlarının her biri aynı görünüme sahiptir. Bu tekrar eden yapıyı her seferinde yeniden yazmak yerine `SarkirSatiri` adında ayrı bir composable tanımlanmış ve farklı şarkı isimleriyle üç kez çağrılmıştır. Bu yaklaşım hem kod tekrarını önler hem de ileride değişiklik yapılmasını kolaylaştırır.

---

## Özet

Bu alıştırmada `Column`, `Row`, `Text`, `Icon`, `Spacer` ve `HorizontalDivider` bileşenleri iç içe kullanılarak gerçek bir uygulamada görülebilecek düzenli ve sade bir kart tasarımı oluşturulmuştur. `shadow`, `RoundedCornerShape` ve `background` gibi görsel detaylar eklenerek kartın modern bir görünüm kazanması sağlanmıştır.


# Modifier Nedir? Zincirleme (Chaining) Yapısı

## Modifier Nedir?

Jetpack Compose'da her bileşenin varsayılan bir görünümü vardır. `Text` düz siyah metin yazar, `Icon` küçük bir sembol çizer, `Column` bileşenleri alt alta dizer. Ancak bu varsayılan görünümler çoğu zaman yeterli değildir: bir bileşenin belirli bir boyutu olması, belirli bir renk taşıması, tıklanabilir ya da kenarlıklı olması istenebilir.

`Modifier`, bir bileşenin **nasıl görüneceğini, ne kadar yer kaplayacağını ve nasıl davranacağını** belirleyen araçtır. Hemen hemen tüm @Composable fonksiyonlar `modifier` parametresi kabul eder.

Bunu bir kıyafet üzerine aksesuar takmaya benzetmek mümkündür. Kıyafetin kendisi bileşendir; kemer, kolye ve ceket ise modifier'lardır. Her aksesuar kıyafete ayrı bir özellik katar. Modifier da bir bileşene tıpkı aksesuar gibi özellikler ekler.

```kotlin
// MainActivity.kt

package com.example.modifiertemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ModifierOrnegi()
        }
    }
}

@Composable
fun ModifierOrnegi() {
    // Modifier olmadan: sade, varsayılan görünüm
    Text(
        text = "Modifier yok",
        fontSize = 16.sp
    )

    // Modifier ile: arka plan rengi ve iç boşluk eklenmiş
    Text(
        text = "Modifier var",
        fontSize = 16.sp,
        modifier = Modifier
            .background(Color(0xFFBBDEFB))
            .padding(12.dp)
    )
}

@Preview(showBackground = true)
@Composable
fun ModifierOrnegiOnizleme() {
    ModifierOrnegi()
}
```

İki `Text` bileşeni aynı metni yazar; ancak modifier eklenen ikincisi mavi arka planlı ve iç boşluklu görünür.

---

## Zincirleme (Chaining) Yapısı

Bir bileşene tek bir modifier yetmeyebilir. Boyut, arka plan, kenar boşluğu ve tıklanabilirlik gibi birden fazla özellik aynı anda eklenebilir. Bunun için modifier'lar **zincir halinde** birbirine bağlanır.

Zincirleme, her modifier'ın sonuna nokta (`.`) koyularak bir sonrakinin eklenmesiyle oluşturulur. Bu zincirin uzunluğunun herhangi bir sınırı yoktur; istenildiği kadar modifier art arda eklenebilir.

Bunu bir montaj bandına benzetmek mümkündür. Ürün bandın üzerinden geçerken her istasyonda yeni bir özellik kazanır: önce boyanır, sonra cilalanır, ardından paketlenir. Modifier zinciri de tam olarak bu şekilde çalışır — bileşen zincir boyunca ilerlerken her modifier ona yeni bir özellik ekler.

```kotlin
// MainActivity.kt

package com.example.chainingornegi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ZincirlemeyiGoster()
        }
    }
}

@Composable
fun ZincirlemeyiGoster() {
    Column(modifier = Modifier.padding(16.dp)) {

        // Adım adım zincir: her satır bir öncekine yeni özellik ekler

        // Yalnızca genişlik
        Text(
            text = "1. Yalnızca genişlik",
            modifier = Modifier
                .fillMaxWidth()
        )

        Text(text = "──────────────────", fontSize = 10.sp, color = Color.LightGray)

        // Genişlik + arka plan
        Text(
            text = "2. Genişlik + arka plan",
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFE3F2FD))
        )

        Text(text = "──────────────────", fontSize = 10.sp, color = Color.LightGray)

        // Genişlik + arka plan + iç boşluk
        Text(
            text = "3. Genişlik + arka plan + padding",
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFE8F5E9))
                .padding(12.dp)
        )

        Text(text = "──────────────────", fontSize = 10.sp, color = Color.LightGray)

        // Genişlik + arka plan + iç boşluk + köşe yuvarlama + kenarlık
        Text(
            text = "4. Tüm özellikler bir arada",
            fontWeight = FontWeight.Bold,
            modifier = Modifier
                .fillMaxWidth()
                .background(
                    color = Color(0xFFFFF9C4),
                    shape = RoundedCornerShape(10.dp)
                )
                .border(
                    width = 1.dp,
                    color = Color(0xFFF9A825),
                    shape = RoundedCornerShape(10.dp)
                )
                .padding(14.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ZincirlemeyiGosterOnizleme() {
    ZincirlemeyiGoster()
}
```

Her adımda zincire bir modifier daha eklenmiş ve bileşen giderek daha fazla özellik kazanmıştır. Dördüncü adımda `fillMaxWidth`, `background`, `border` ve `padding` modifier'ları art arda zincirlenmiş; sonuçta yuvarlak köşeli, kenarlıklı, renkli arka planlı ve iç boşluklu bir metin kutusu elde edilmiştir.

---

## Modifier Nesnesini Ayrı Tanımlamak

Aynı modifier zinciri birden fazla bileşende kullanılacaksa her seferinde yeniden yazmak yerine bir değişkende saklanabilir. Bu, kodu hem kısaltır hem de tutarlı hale getirir.

```kotlin
// MainActivity.kt

package com.example.modifierdegisken

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            TekrarKullanilanModifier()
        }
    }
}

@Composable
fun TekrarKullanilanModifier() {

    // Ortak modifier zinciri bir kez tanımlanır.
    // Bunu bir şablon mühür olarak düşünmek mümkündür:
    // mühür hazırlanır, farklı kağıtlara tekrar tekrar vurulur.
    val kartModifier = Modifier
        .fillMaxWidth()
        .background(
            color = Color(0xFFF3E5F5),
            shape = RoundedCornerShape(12.dp)
        )
        .padding(14.dp)

    Column(modifier = Modifier.padding(16.dp)) {
        Text(
            text = "Birinci Kart",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold,
            modifier = kartModifier  // Şablon burada kullanılır
        )

        Text(text = "", modifier = Modifier.padding(4.dp)) // Boşluk için

        Text(
            text = "İkinci Kart",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold,
            modifier = kartModifier  // Aynı şablon burada da kullanılır
        )

        Text(text = "", modifier = Modifier.padding(4.dp))

        Text(
            text = "Üçüncü Kart",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold,
            modifier = kartModifier  // Ve burada da
        )
    }
}

@Preview(showBackground = true)
@Composable
fun TekrarKullanilanModifierOnizleme() {
    TekrarKullanilanModifier()
}
```

`kartModifier` değişkeni bir kez tanımlanmış ve üç farklı `Text` bileşenine uygulanmıştır. Kartların rengini değiştirmek gerektiğinde yalnızca bu değişkenin içindeki renk güncellenir; üç bileşenin tamamı otomatik olarak değişir.

---

## Özet

`Modifier`, bir bileşenin boyutunu, görünümünü ve davranışını belirleyen araçtır. Modifier'lar nokta (`.`) ile art arda zincirlenebilir; her halka zincire yeni bir özellik katar. Sık kullanılan modifier zincirleri değişkenlerde saklanarak tekrar tekrar kullanılabilir. Modifier sistemi, Jetpack Compose'un en temel ve en sık kullanılan mekanizmalarından biridir.




# Boyut Modifier'ları: size, fillMaxWidth, fillMaxHeight, wrapContentSize

## Boyut Neden Önemlidir?

Bir bileşen ekrana çizildiğinde ne kadar alan kaplayacağı belirtilmezse Compose, bileşeni yalnızca içeriği kadar küçük tutar. Bir `Text` yalnızca metnin genişliği kadar, bir `Column` yalnızca içindeki bileşenlerin toplam yüksekliği kadar yer kaplar.

Ancak gerçek uygulamalarda bileşenlerin belirli boyutlarda olması gerekebilir. Bir düğmenin ekranın tamamına yayılması, bir resmin tam kare görünmesi ya da bir kutunun ekranın yarısını kaplaması istenebilir. Tüm bu boyut ayarlamaları **boyut modifier'ları** ile yapılır.

Bunu bir mobilyacının odayı döşemesine benzetmek mümkündür. Koltuk oturma odasına yerleştirildiğinde "koltuğun tam ne kadar yer kaplayacağına" karar vermek gerekir. Boyut modifier'ları da bileşenlere tam olarak bu kararı aldırır.

---

## size(): Sabit Boyut

`size()` modifier'ı bir bileşenin genişliğini ve yüksekliğini **sabit** bir değere ayarlar. Her iki boyut eşit olacaksa tek bir değer, farklı olacaksa `width` ve `height` parametreleri ayrı ayrı verilir.

```kotlin
// MainActivity.kt

package com.example.sizemodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SizeOrnegi()
        }
    }
}

@Composable
fun SizeOrnegi() {
    Column(modifier = Modifier.padding(16.dp)) {

        // Tek değer: genişlik ve yükseklik eşit (kare)
        Row(
            horizontalArrangement = Arrangement.spacedBy(12.dp)
        ) {
            // 40x40 dp kare kutu
            Spacer(
                modifier = Modifier
                    .size(40.dp)
                    .background(Color(0xFF1565C0))
            )
            // 70x70 dp kare kutu
            Spacer(
                modifier = Modifier
                    .size(70.dp)
                    .background(Color(0xFF1565C0))
            )
            // 100x100 dp kare kutu
            Spacer(
                modifier = Modifier
                    .size(100.dp)
                    .background(Color(0xFF1565C0))
            )
        }

        Spacer(modifier = Modifier.height(24.dp))

        // Ayrı genişlik ve yükseklik: dikdörtgen kutu
        Spacer(
            modifier = Modifier
                .width(200.dp)
                .height(60.dp)
                .background(Color(0xFF2E7D32))
        )
    }
}

@Preview(showBackground = true)
@Composable
fun SizeOrnegiOnizleme() {
    SizeOrnegi()
}
```

`size(40.dp)` bileşeni hem 40 dp genişliğinde hem de 40 dp yüksekliğinde yapar. `width(200.dp).height(60.dp)` ise genişliği 200, yüksekliği 60 dp olan bir dikdörtgen oluşturur.

---

## fillMaxWidth(): Tam Genişlik

`fillMaxWidth()`, bileşenin **üst bileşeninin tüm genişliğini kaplamasını** sağlar. Yükseklik değişmez; yalnızca genişlik etkilenir.

Bunu bir masa örtüsüne benzetmek mümkündür. Masa örtüsü masanın tam genişliğini kaplar; ne daha dar ne daha geniş. `fillMaxWidth()` de bileşeni tam olarak kapsayıcısının genişliğine getirir.

`fillMaxWidth()` içine `fraction` parametresi verilerek bileşen kapsayıcının yalnızca belirli bir oranını kaplayacak şekilde ayarlanabilir. Örneğin `fillMaxWidth(0.5f)` bileşeni kapsayıcının yarı genişliğine getirir.

```kotlin
// MainActivity.kt

package com.example.fillmaxwidthornegi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            FillMaxWidthOrnegi()
        }
    }
}

@Composable
fun GenislikCubugu(etiket: String, oran: Float, renk: Color) {
    Text(
        text = "$etiket (${(oran * 100).toInt()}%)",
        fontSize = 13.sp,
        fontWeight = FontWeight.Medium,
        color = Color.White,
        modifier = Modifier
            .fillMaxWidth(oran)   // Kapsayıcının belirtilen oranı kadar genişler
            .background(renk)
            .padding(horizontal = 10.dp, vertical = 8.dp)
    )
}

@Composable
fun FillMaxWidthOrnegi() {
    Column(
        modifier = Modifier
            .padding(16.dp)
            .fillMaxWidth()
    ) {
        Text(
            text = "fillMaxWidth() Oranları",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )
        Spacer(modifier = Modifier.height(12.dp))

        GenislikCubugu("Tam genişlik", 1.0f, Color(0xFF1565C0))
        Spacer(modifier = Modifier.height(6.dp))
        GenislikCubugu("Üçte iki", 0.66f, Color(0xFF2E7D32))
        Spacer(modifier = Modifier.height(6.dp))
        GenislikCubugu("Yarısı", 0.5f, Color(0xFFC62828))
        Spacer(modifier = Modifier.height(6.dp))
        GenislikCubugu("Çeyreği", 0.25f, Color(0xFFE65100))
    }
}

@Preview(showBackground = true)
@Composable
fun FillMaxWidthOrnegiOnizleme() {
    FillMaxWidthOrnegi()
}
```

Her çubuk `fillMaxWidth()` ile kapsayıcının farklı bir oranını kaplar. Bu görsel, `fraction` parametresinin nasıl çalıştığını çok net biçimde ortaya koymaktadır.

---

## fillMaxHeight(): Tam Yükseklik

`fillMaxHeight()`, `fillMaxWidth()`'in dikey eksendeki karşılığıdır. Bileşenin **kapsayıcısının tüm yüksekliğini kaplamasını** sağlar. Genişlik değişmez.

Tıpkı `fillMaxWidth()`'te olduğu gibi, `fillMaxHeight(fraction)` ile yalnızca belirli bir oran belirtilebilir.

```kotlin
// MainActivity.kt

package com.example.fillmaxheightornegi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            FillMaxHeightOrnegi()
        }
    }
}

@Composable
fun YukseklikSutunu(etiket: String, oran: Float, renk: Color) {
    Column(
        modifier = Modifier
            .width(60.dp)
            .fillMaxHeight(oran)   // Kapsayıcının belirtilen oranı kadar yükselir
            .background(renk)
            .padding(4.dp),
        verticalArrangement = Arrangement.Bottom,
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(
            text = etiket,
            fontSize = 11.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center
        )
    }
}

@Composable
fun FillMaxHeightOrnegi() {
    // fillMaxHeight'ın çalışması için Row'un belirli bir yüksekliği olması gerekir.
    // fillMaxSize() ile hem genişlik hem yükseklik tam ekran yapılır.
    Row(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        horizontalArrangement = Arrangement.spacedBy(12.dp),
        verticalAlignment = Alignment.Bottom
    ) {
        YukseklikSutunu("%25", 0.25f, Color(0xFF1565C0))
        YukseklikSutunu("%50", 0.50f, Color(0xFF2E7D32))
        YukseklikSutunu("%75", 0.75f, Color(0xFFC62828))
        YukseklikSutunu("%100", 1.00f, Color(0xFF6A1B9A))
    }
}

@Preview(showBackground = true)
@Composable
fun FillMaxHeightOrnegiOnizleme() {
    FillMaxHeightOrnegi()
}
```

Her sütun `fillMaxHeight()` ile kapsayıcının farklı bir oranı kadar yükselir. Bu yapı basit bir çubuk grafik görünümü oluşturur.

---

## wrapContentSize(): İçerik Kadar

`wrapContentSize()`, bileşenin **yalnızca içeriği kadar yer kaplamasını** sağlar. Ancak asıl kullanım amacı bundan biraz farklıdır: bileşenin kapsayıcısı içinde **hizalanmasını** sağlamak için kullanılır.

Bunu bir şekere benzetmek mümkündür. Şeker küçüktür ama büyük bir kutunun içine konulmuştur. `wrapContentSize(Alignment.Center)` şekeri kutunun tam ortasına yerleştirir; `Alignment.TopEnd` ise sağ üst köşeye.

```kotlin
// MainActivity.kt

package com.example.wrapcontentornegi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.wrapContentSize
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            WrapContentOrnegi()
        }
    }
}

// Görsel fark için renkli arka planlı kapsayıcı kutu
@Composable
fun HizalamaKutusu(baslik: String, hizalama: Alignment) {
    Text(
        text = baslik,
        fontSize = 12.sp,
        color = Color.Gray
    )
    Spacer(modifier = Modifier.height(4.dp))
    // Dış kutu: geniş ve yüksek (kapsayıcı)
    Spacer(
        modifier = Modifier
            .fillMaxWidth()
            .height(80.dp)
            .background(Color(0xFFEEEEEE))
            // wrapContentSize ile içerik belirtilen köşeye hizalanır
            .wrapContentSize(hizalama)
    )
    // İç içerik: küçük renkli kare (hizalanacak olan bileşen)
    // Not: Bu satır görsel amaçlıdır; gerçekte wrapContentSize
    // Spacer'a değil, içerik bileşenine uygulanır.
    Spacer(modifier = Modifier.height(12.dp))
}

@Composable
fun WrapContentOrnegi() {
    Column(modifier = Modifier.padding(16.dp)) {
        Text(
            text = "wrapContentSize Hizalama Örnekleri",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )
        Spacer(modifier = Modifier.height(16.dp))

        // Ortada küçük içerik
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .height(80.dp)
                .background(Color(0xFFEEEEEE))
                .wrapContentSize(Alignment.Center)
        ) {
            Text(
                text = "Merkez",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
                modifier = Modifier
                    .background(Color(0xFF1565C0))
                    .padding(horizontal = 12.dp, vertical = 6.dp),
                color = Color.White
            )
        }

        Spacer(modifier = Modifier.height(12.dp))

        // Sol üstte küçük içerik
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .height(80.dp)
                .background(Color(0xFFEEEEEE))
                .wrapContentSize(Alignment.TopStart)
        ) {
            Text(
                text = "Sol Üst",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
                modifier = Modifier
                    .background(Color(0xFF2E7D32))
                    .padding(horizontal = 12.dp, vertical = 6.dp),
                color = Color.White
            )
        }

        Spacer(modifier = Modifier.height(12.dp))

        // Sağ altta küçük içerik
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .height(80.dp)
                .background(Color(0xFFEEEEEE))
                .wrapContentSize(Alignment.BottomEnd)
        ) {
            Text(
                text = "Sağ Alt",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
                modifier = Modifier
                    .background(Color(0xFFC62828))
                    .padding(horizontal = 12.dp, vertical = 6.dp),
                color = Color.White
            )
        }
    }
}

@Preview(showBackground = true)
@Composable
fun WrapContentOrnegiOnizleme() {
    WrapContentOrnegi()
}
```

---

## Dört Modifier'ın Karşılaştırması

| Modifier | Ne Yapar? | Ne Zaman Kullanılır? |
|---|---|---|
| `size(x.dp)` | Sabit genişlik ve yükseklik | Belirli boyuttaki kutular, ikonlar |
| `fillMaxWidth()` | Kapsayıcının tüm genişliğini kapla | Düğmeler, kart içerikleri, metin alanları |
| `fillMaxHeight()` | Kapsayıcının tüm yüksekliğini kapla | Yan sütunlar, dikey çubuk grafikler |
| `wrapContentSize()` | İçerik kadar yer kap, belirtilen köşeye hizala | Küçük içeriği büyük kapsayıcı içinde konumlandırma |

---

## Özet

Boyut modifier'ları, bileşenlerin ekranda ne kadar yer kaplayacağını ve nereye hizalanacağını belirler. `size()` sabit boyut, `fillMaxWidth()` ve `fillMaxHeight()` kapsayıcıyı doldurma, `wrapContentSize()` ise içeriği belirli bir köşeye hizalama işlevlerini üstlenir. Bu modifier'lar zincirlenebilir ve birlikte kullanıldığında her türlü boyut düzeni elde edilebilir.


# Görünüm Modifier'ları: background, border, clip, shadow

## Görünüm Modifier'ları Nedir?

Bir bileşenin boyutu ayarlandıktan sonra sıradaki adım görünümünü belirlemektir. Arka plan rengi, kenarlık çizgisi, köşe şekli ve gölge efekti — bunların tamamı **görünüm modifier'ları** ile ayarlanır.

Bunu bir resim çerçevesine benzetmek mümkündür. Resmin kendisi bileşendir; çerçevenin rengi `background`, çerçevenin kenar çizgisi `border`, çerçevenin köşe biçimi `clip`, çerçevenin masadan hafifçe yükselmesi ise `shadow`'dur. Bu dört modifier bir arada kullanıldığında sıradan bir bileşen görsel olarak tam anlamıyla şekillendirilmiş olur.

---

## background(): Arka Plan Rengi

`background()` modifier'ı bir bileşenin arkasına renk ya da şekil ekler. Yalnızca renk verilebileceği gibi `shape` parametresiyle birlikte yuvarlak köşeli ya da tamamen daire biçimli arka planlar da oluşturulabilir.

```kotlin
// MainActivity.kt

package com.example.backgroundmodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            BackgroundOrnegi()
        }
    }
}

@Composable
fun BackgroundOrnegi() {
    Column(
        modifier = Modifier.padding(16.dp),
        verticalArrangement = Arrangement.spacedBy(12.dp)
    ) {
        // Düz renk arka plan
        Text(
            text = "Düz renk arka plan",
            fontSize = 15.sp,
            modifier = Modifier
                .background(Color(0xFFBBDEFB))
                .padding(12.dp)
        )

        // Yuvarlak köşeli arka plan
        // RoundedCornerShape içindeki değer köşe yarıçapını belirtir.
        // Değer arttıkça köşeler daha yuvarlak görünür.
        Text(
            text = "Yuvarlak köşeli arka plan",
            fontSize = 15.sp,
            modifier = Modifier
                .background(
                    color = Color(0xFFC8E6C9),
                    shape = RoundedCornerShape(12.dp)
                )
                .padding(12.dp)
        )

        // Tam daire arka plan
        // CircleShape bileşeni daire biçimine sokar.
        // Daire için genişlik ve yüksekliğin eşit olması gerekir.
        Row(
            verticalAlignment = Alignment.CenterVertically,
            horizontalArrangement = Arrangement.spacedBy(8.dp)
        ) {
            Text(
                text = "A",
                fontSize = 18.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                modifier = Modifier
                    .size(44.dp)
                    .background(
                        color = Color(0xFF1565C0),
                        shape = CircleShape
                    )
                    .padding(10.dp)
            )
            Text(
                text = "Daire arka plan",
                fontSize = 15.sp
            )
        }
    }
}

@Preview(showBackground = true)
@Composable
fun BackgroundOrnegiOnizleme() {
    BackgroundOrnegi()
}
```

---

## border(): Kenarlık Çizgisi

`border()` modifier'ı bileşenin çevresine bir kenarlık çizgisi ekler. Üç temel parametre alır: `width` (çizginin kalınlığı), `color` (çizginin rengi) ve `shape` (kenarlığın şekli).

Bunu bir resim paspartusu olarak düşünmek mümkündür. Paspartu resmin çevresine çerçeve görevi gören ince bir kenar ekler; resmin kendisine dokunmaz, sadece onu çevreler. `border()` da tam olarak budur: bileşenin dışına ince ya da kalın bir çizgi çizer.

```kotlin
// MainActivity.kt

package com.example.bordermodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.border
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            BorderOrnegi()
        }
    }
}

@Composable
fun BorderOrnegi() {
    Column(
        modifier = Modifier.padding(16.dp),
        verticalArrangement = Arrangement.spacedBy(14.dp)
    ) {

        // İnce düz kenarlık
        Text(
            text = "İnce mavi kenarlık",
            fontSize = 15.sp,
            modifier = Modifier
                .border(
                    width = 1.dp,
                    color = Color(0xFF1565C0)
                )
                .padding(12.dp)
        )

        // Kalın yuvarlak köşeli kenarlık
        Text(
            text = "Kalın yeşil kenarlık",
            fontSize = 15.sp,
            modifier = Modifier
                .border(
                    width = 3.dp,
                    color = Color(0xFF2E7D32),
                    shape = RoundedCornerShape(10.dp)
                )
                .padding(12.dp)
        )

        // background ve border birlikte kullanımı
        // Önce background, sonra border zincire eklenir.
        // Modifier sırası burada önemlidir: bir sonraki derste ele alınır.
        Text(
            text = "Arka plan + kenarlık",
            fontSize = 15.sp,
            modifier = Modifier
                .border(
                    width = 2.dp,
                    color = Color(0xFFC62828),
                    shape = RoundedCornerShape(8.dp)
                )
                .padding(12.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun BorderOrnegiOnizleme() {
    BorderOrnegi()
}
```

---

## clip(): Şekle Göre Kırpma

`clip()` modifier'ı bileşeni verilen şeklin sınırları içine **kırpar**. Bileşenin taşan kısımları görünmez hale gelir.

Bunu bir kurabiye kalıbına benzetmek mümkündür. Hamur (bileşen) kalıbın üzerine basılır; kalıbın dışında kalan hamur kesilir. `clip(CircleShape)` bileşeni tam daire biçiminde kırpar, kalıbın dışına taşan hiçbir şey görünmez.

`clip()` özellikle profil resimlerini yuvarlak göstermek için sıkça kullanılır.

```kotlin
// MainActivity.kt

package com.example.clipmodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.CutCornerShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ClipOrnegi()
        }
    }
}

// Şekil etiketiyle birlikte kırpılmış kutu
@Composable
fun KirpilmisKutu(etiket: String, modifier: Modifier) {
    Column(
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Spacer(
            modifier = modifier
                .size(72.dp)
                .background(Color(0xFF1565C0))
        )
        Spacer(modifier = Modifier.height(6.dp))
        Text(text = etiket, fontSize = 12.sp, color = Color.Gray)
    }
}

@Composable
fun ClipOrnegi() {
    Column(modifier = Modifier.padding(16.dp)) {

        Text(
            text = "Farklı Kırpma Şekilleri",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )

        Spacer(modifier = Modifier.height(16.dp))

        Row(horizontalArrangement = Arrangement.spacedBy(16.dp)) {

            // Kırpma yok: köşeli dikdörtgen
            KirpilmisKutu(
                etiket = "Kırpmasız",
                modifier = Modifier
            )

            // Daire kırpma
            KirpilmisKutu(
                etiket = "CircleShape",
                modifier = Modifier.clip(CircleShape)
            )

            // Yuvarlak köşe kırpma
            KirpilmisKutu(
                etiket = "Rounded",
                modifier = Modifier.clip(RoundedCornerShape(16.dp))
            )

            // Kesik köşe kırpma
            KirpilmisKutu(
                etiket = "CutCorner",
                modifier = Modifier.clip(CutCornerShape(12.dp))
            )
        }

        Spacer(modifier = Modifier.height(24.dp))

        // Gerçek kullanım: profil resmi daire kırpma
        Text(
            text = "Profil resmi (daire kırpma)",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )
        Spacer(modifier = Modifier.height(12.dp))
        Row(verticalAlignment = Alignment.CenterVertically) {
            Image(
                painter = painterResource(
                    id = android.R.drawable.sym_def_app_icon
                ),
                contentDescription = "Profil resmi",
                contentScale = ContentScale.Crop,
                modifier = Modifier
                    .size(56.dp)
                    .clip(CircleShape)  // Resim daire biçiminde kırpılır
            )
            Spacer(modifier = Modifier.width(12.dp))
            Column {
                Text(
                    text = "Ali Yılmaz",
                    fontSize = 16.sp,
                    fontWeight = FontWeight.Bold
                )
                Text(
                    text = "Android Geliştirici",
                    fontSize = 13.sp,
                    color = Color.Gray
                )
            }
        }
    }
}

@Preview(showBackground = true)
@Composable
fun ClipOrnegiOnizleme() {
    ClipOrnegi()
}
```

---

## shadow(): Gölge Efekti

`shadow()` modifier'ı bileşenin altına bir gölge ekler. Bu gölge, bileşenin sayfadan hafifçe yükseltilmiş gibi görünmesini sağlar ve derinlik hissi yaratır.

Bunu güneş altındaki bir nesnenin gölgesine benzetmek mümkündür. Bir kutu yere ne kadar yakın duruyorsa gölgesi o kadar küçük ve keskin görünür; kutu yükseldiğinde gölge büyür ve yumuşar. `elevation` parametresi tam olarak bu "yükseklik" değerini belirler.

`shadow()` modifier'ı `shape` parametresi de alır. Bu parametre verilmezse gölge dikdörtgen biçiminde oluşur; `RoundedCornerShape` verilirse gölge de bileşenin köşeleriyle uyumlu yuvarlak bir biçim alır.

```kotlin
// MainActivity.kt

package com.example.shadowmodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.shadow
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ShadowOrnegi()
        }
    }
}

@Composable
fun GolgeliKart(etiket: String, yukseklik: Int) {
    // shadow modifier'ı background'dan ÖNCE gelmelidir.
    // Aksi takdirde gölge arka planın altında kalır ve görünmez.
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .shadow(
                elevation = yukseklik.dp,
                shape = RoundedCornerShape(12.dp)
            )
            .background(
                color = Color.White,
                shape = RoundedCornerShape(12.dp)
            )
            .padding(16.dp)
    ) {
        Text(
            text = etiket,
            fontSize = 15.sp,
            fontWeight = FontWeight.Medium
        )
        Text(
            text = "elevation = ${yukseklik}.dp",
            fontSize = 13.sp,
            color = Color.Gray
        )
    }
}

@Composable
fun ShadowOrnegi() {
    Column(
        modifier = Modifier
            .background(Color(0xFFF5F5F5))
            .padding(20.dp),
        verticalArrangement = Arrangement.spacedBy(20.dp)
    ) {
        Text(
            text = "Farklı Gölge Yükseklikleri",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )

        GolgeliKart("Hafif gölge", 2)
        GolgeliKart("Orta gölge", 8)
        GolgeliKart("Derin gölge", 20)
    }
}

@Preview(showBackground = true)
@Composable
fun ShadowOrnegiOnizleme() {
    ShadowOrnegi()
}
```

---

## Dört Modifier'ı Bir Arada Kullanmak

Gerçek uygulamalarda bu dört modifier çoğunlukla birlikte kullanılır. Aşağıdaki örnek, dört modifier'ın birlikte oluşturduğu modern kart tasarımını göstermektedir:

```kotlin
// MainActivity.kt

package com.example.gorununumbirlikte

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Star
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.draw.shadow
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            Column(
                modifier = Modifier
                    .background(Color(0xFFF5F5F5))
                    .padding(20.dp)
            ) {
                ModernKart()
            }
        }
    }
}

@Composable
fun ModernKart() {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            // 1. shadow: gölge efekti (background'dan önce gelmeli)
            .shadow(elevation = 8.dp, shape = RoundedCornerShape(16.dp))
            // 2. background: beyaz arka plan, yuvarlak köşeli
            .background(color = Color.White, shape = RoundedCornerShape(16.dp))
            // 3. padding: iç boşluk
            .padding(16.dp)
    ) {
        Row(verticalAlignment = Alignment.CenterVertically) {

            // Avatar: clip + background + border birlikte
            Column(
                modifier = Modifier
                    .size(52.dp)
                    // 1. clip: daire biçimine kırp
                    .clip(CircleShape)
                    // 2. background: mavi daire arka plan
                    .background(Color(0xFF1565C0))
                    // 3. border: altın kenarlık
                    .border(2.dp, Color(0xFFFFD700), CircleShape),
                horizontalAlignment = Alignment.CenterHorizontally,
                verticalArrangement = androidx.compose.foundation.layout.Arrangement.Center
            ) {
                Text(
                    text = "AY",
                    fontSize = 18.sp,
                    fontWeight = FontWeight.Bold,
                    color = Color.White
                )
            }

            Spacer(modifier = Modifier.width(14.dp))

            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text = "Ali Yılmaz",
                    fontSize = 16.sp,
                    fontWeight = FontWeight.Bold
                )
                Text(
                    text = "Android Geliştirici",
                    fontSize = 13.sp,
                    color = Color.Gray
                )
            }

            Icon(
                imageVector = Icons.Filled.Star,
                contentDescription = null,
                tint = Color(0xFFFFD700),
                modifier = Modifier.size(22.dp)
            )
        }

        Spacer(modifier = Modifier.height(12.dp))

        Text(
            text = "background, border, clip ve shadow modifier'larının " +
                   "bir arada kullanıldığı modern kart örneği.",
            fontSize = 14.sp,
            color = Color(0xFF424242),
            lineHeight = 20.sp
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ModernKartOnizleme() {
    Column(
        modifier = Modifier
            .background(Color(0xFFF5F5F5))
            .padding(20.dp)
    ) {
        ModernKart()
    }
}
```

---

## Özet

| Modifier | Görevi | Sık Kullanım Yeri |
|---|---|---|
| `background()` | Arka plan rengi ve şekli | Kartlar, etiketler, avatar arka planları |
| `border()` | Çevre kenarlık çizgisi | Seçili öğeler, form alanları |
| `clip()` | Şekle göre kırpma | Profil resimleri, yuvarlak köşeli kartlar |
| `shadow()` | Gölge efekti | Kartlar, düğmeler, yükseltilmiş bileşenler |

Bu dört modifier, Jetpack Compose'da görsel açıdan zengin ve modern arayüzler oluşturmanın temel araçlarıdır. Birlikte zincirlendiğinde sıradan bir bileşen profesyonel bir görünüm kazanır.


# Boşluk Modifier'ları: padding (İç Boşluk)

## padding Nedir?

Bir bileşenin içeriği ile bileşenin kenarları arasındaki boşluğa **iç boşluk** adı verilir. Jetpack Compose'da bu boşluk `padding()` modifier'ı ile ayarlanır.

Bunu bir hediye kutusuna benzetmek mümkündür. Hediye, kutunun içine konulduğunda kutunun kenarlarına yapışık durmaz; çevresine koruyucu köpük ya da kâğıt yerleştirilir. `padding()` da tam olarak bu köpüktür: içerik ile kutunun kenarı arasına nefes alacak alan açar.

`padding()` olmadan bir `Text` bileşeninin arka planı metnin tam üzerine yapışır; metin kenarlardan taşıyor gibi görünür. `padding()` eklenerek metin ile arka plan arasına boşluk bırakılır.

---

## padding'in Temel Kullanım Biçimleri

`padding()` modifier'ı birkaç farklı şekilde kullanılabilir. Her biri farklı bir boşluk düzeni oluşturur.

**Tüm kenarlara eşit boşluk:** Tek bir değer verildiğinde dört kenarın tamamına aynı boşluk uygulanır.

**Yatay ve dikey ayrı ayrı:** `horizontal` ve `vertical` parametreleriyle yatay ve dikey boşluklar birbirinden bağımsız ayarlanır.

**Her kenara ayrı ayrı:** `start`, `end`, `top` ve `bottom` parametreleriyle dört kenar birbirinden tamamen bağımsız olarak ayarlanır.

```kotlin
// MainActivity.kt

package com.example.paddingkullanim

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            PaddingKullanim()
        }
    }
}

@Composable
fun PaddingKullanim() {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp)
    ) {
        Text(
            text = "Padding Türleri",
            fontSize = 18.sp,
            fontWeight = FontWeight.Bold
        )

        Spacer(modifier = Modifier.height(16.dp))

        // 1. Padding yok
        Text(
            text = "Padding yok — metin kenara yapışık",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF1565C0))
        )

        Spacer(modifier = Modifier.height(10.dp))

        // 2. Tüm kenarlara eşit padding
        Text(
            text = "Tüm kenarlara 12.dp",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF2E7D32))
                .padding(12.dp)       // ← dört kenara eşit
        )

        Spacer(modifier = Modifier.height(10.dp))

        // 3. Yatay ve dikey ayrı
        Text(
            text = "Yatay 24.dp — Dikey 8.dp",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFC62828))
                .padding(           // ← yatay ve dikey ayrı
                    horizontal = 24.dp,
                    vertical = 8.dp
                )
        )

        Spacer(modifier = Modifier.height(10.dp))

        // 4. Her kenara ayrı ayrı
        Text(
            text = "Üst:4 Alt:16 Sol:32 Sağ:4",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF6A1B9A))
                .padding(           // ← dört kenar bağımsız
                    top = 4.dp,
                    bottom = 16.dp,
                    start = 32.dp,
                    end = 4.dp
                )
        )
    }
}

@Preview(showBackground = true)
@Composable
fun PaddingKullanımOnizleme() {
    PaddingKullanim()
}
```

Her örnekte arka plan rengi kasıtlı olarak eklenmiştir. Arka plan olmadan `padding`'in etkisi gözle görülmez; arka plan eklendiğinde ise boşluğun tam olarak nerede oluştuğu net biçimde ortaya çıkar.

---

## padding'in Modifier Zincirindeki Yeri Önemlidir

`padding()` modifier'ı zincirde nereye yazıldığı, sonucu doğrudan etkiler. Bu kural bir önceki derste "Modifier sırasının önemi" başlığı altında ayrıntılı ele alınacaktır; ancak `padding` ile `background` arasındaki ilişki burada da dikkat çekmektedir.

Bunu bir evin duvarları ile iç dekorasyonu arasındaki farka benzetmek mümkündür. Duvar boyandıktan sonra mobilya içeri alınırsa mobilya boyanmış alanın içinde kalır. Ancak mobilya konulduktan sonra duvar boyanırsa boya mobilyanın üzerine de çıkar. `padding` ve `background`'un sırası da tam olarak bu şekilde sonucu değiştirir.

```kotlin
// MainActivity.kt

package com.example.paddingsirasi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            PaddingSirasi()
        }
    }
}

@Composable
fun PaddingSirasi() {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp)
    ) {
        Text(
            text = "Modifier Sırası Farkı",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )

        Spacer(modifier = Modifier.height(16.dp))

        // Önce background, sonra padding:
        // Arka plan metnin tam çevresini sarar.
        // Padding, içeriği arka planın içinde iter.
        Text(
            text = "Önce background → sonra padding",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF1565C0))  // ← önce arka plan
                .padding(16.dp)                  // ← sonra iç boşluk
        )

        Spacer(modifier = Modifier.height(12.dp))

        // Önce padding, sonra background:
        // Padding önce dışarıdan boşluk açar.
        // Arka plan yalnızca padding'in içinde kalan alana uygulanır.
        Text(
            text = "Önce padding → sonra background",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)                  // ← önce dış boşluk
                .background(Color(0xFF1565C0))   // ← sonra arka plan
        )
    }
}

@Preview(showBackground = true)
@Composable
fun PaddingSirasiOnizleme() {
    PaddingSirasi()
}
```

İlk örnekte arka plan metnin tam çevresini sarar; boşluk içeride kalır. İkinci örnekte ise önce dışarıdan boşluk açılır, arka plan yalnızca geriye kalan iç alana uygulanır. Sonuçta arka planın kapladığı alan küçülür.

---

## Özet

`padding()` modifier'ı bileşenin içeriği ile kenarları arasına boşluk ekler. Tüm kenarlara eşit, yatay/dikey ayrı ayrı ya da dört kenar tamamen bağımsız olarak ayarlanabilir. Modifier zincirindeki yeri sonucu doğrudan etkilediğinden `background` ile birlikte kullanılırken sıraya dikkat edilmesi gerekir: `background` önce gelirse boşluk renkli alanın içinde oluşur; `padding` önce gelirse renkli alan küçülür.


# Etkileşim Modifier'ları: clickable, scrollable

## Etkileşim Modifier'ları Nedir?

Şimdiye kadar ele alınan modifier'lar bileşenlerin nasıl **göründüğünü** belirliyordu. Etkileşim modifier'ları ise bileşenlerin kullanıcıyla nasıl **davrandığını** belirler.

Bir uygulama yalnızca ekrana bakan değil, dokunulan, kaydırılan ve etkileşime girilen bir yapıdır. `clickable` bir bileşeni tıklanabilir yapar; `scrollable` ise içeriğin ekran dışına taştığında kaydırılabilmesini sağlar.

Bunu bir kitap ile dergi arasındaki farka benzetmek mümkündür. Kitap sayfaları sabittir, yalnızca okunur. Dergi ise sayfaları çevrilebilen, etkileşimli bir yapıdır. `clickable` ve `scrollable` modifier'ları bileşenlere bu "çevrilebilir sayfa" özelliğini kazandırır.

---

## clickable: Tıklanabilir Bileşen

Varsayılan olarak `Text`, `Column`, `Row` gibi bileşenler tıklamalara tepki vermez. `clickable` modifier'ı eklenerek herhangi bir bileşen tıklanabilir hale getirilir. Tıklandığında çalışacak kod `onClick` lambda fonksiyonuna yazılır.

```kotlin
// MainActivity.kt

package com.example.clickablemodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ClickableOrnegi()
        }
    }
}

@Composable
fun ClickableOrnegi() {
    // Tıklama sayısını tutan state değişkeni
    var tiklamaSayisi by remember { mutableStateOf(0) }

    Column(
        modifier = Modifier
            .padding(16.dp)
            .fillMaxWidth(),
        verticalArrangement = Arrangement.spacedBy(16.dp)
    ) {
        Text(
            text = "Tıklama sayısı: $tiklamaSayisi",
            fontSize = 18.sp,
            fontWeight = FontWeight.Bold
        )

        // clickable modifier ile herhangi bir bileşen tıklanabilir olur.
        // Yalnızca Button değil, Text, Column, Row da tıklanabilir yapılabilir.
        Text(
            text = "Buraya tıkla",
            fontSize = 16.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF1565C0))
                .clickable {
                    // Tıklandığında bu blok çalışır
                    tiklamaSayisi++
                }
                .padding(14.dp)
        )

        // Tıklama sayısına göre rengi değişen bileşen
        val arkaplanRengi = if (tiklamaSayisi % 2 == 0) {
            Color(0xFF2E7D32)
        } else {
            Color(0xFFC62828)
        }

        Text(
            text = "Renk değiştiren kutu",
            fontSize = 16.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(arkaplanRengi)
                .clickable { tiklamaSayisi++ }
                .padding(14.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ClickableOrnegiOnizleme() {
    ClickableOrnegi()
}
```

---

## clickable ve Ripple Efekti

`clickable` modifier'ı varsayılan olarak tıklandığında Material Design'ın **ripple** (dalgalanma) efektini gösterir. Bu efekt, tıklanan noktadan dışa doğru yayılan bir ışıma animasyonudur ve kullanıcıya dokunuşunun algılandığını görsel olarak bildirir.

Ripple efekti istenilmiyorsa `indication = null` ve `interactionSource` parametreleri verilerek devre dışı bırakılabilir. Ancak çoğu durumda ripple efektini açık bırakmak kullanıcı deneyimini iyileştirir.

```kotlin
// MainActivity.kt

package com.example.rippleornegi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.interaction.MutableInteractionSource
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            RippleOrnegi()
        }
    }
}

@Composable
fun RippleOrnegi() {
    var mesaj by remember { mutableStateOf("Henüz tıklanmadı.") }

    Column(
        modifier = Modifier
            .padding(16.dp)
            .fillMaxWidth(),
        verticalArrangement = Arrangement.spacedBy(12.dp)
    ) {
        Text(text = mesaj, fontSize = 16.sp)

        // Ripple efektli tıklanabilir kutu (varsayılan davranış)
        Text(
            text = "Ripple efektli (varsayılan)",
            fontSize = 15.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF1565C0))
                .clickable { mesaj = "Ripple kutusuna tıklandı." }
                .padding(14.dp)
        )

        // Ripple efektsiz tıklanabilir kutu
        Text(
            text = "Ripple efektsiz",
            fontSize = 15.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF6A1B9A))
                .clickable(
                    indication = null,
                    interactionSource = remember { MutableInteractionSource() }
                ) { mesaj = "Ripple yok kutusuna tıklandı." }
                .padding(14.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun RippleOrnegiOnizleme() {
    RippleOrnegi()
}
```

---

## scrollable: Kaydırılabilir İçerik

Bir `Column` içindeki içerik ekranın yüksekliğini aştığında fazladan içerik ekranın altına taşar ve görünmez olur. `verticalScroll` modifier'ı eklenerek içerik yukarı-aşağı kaydırılabilir hale getirilir.

`verticalScroll`, bir `ScrollState` nesnesi gerektirir. Bu nesne `rememberScrollState()` ile oluşturulur ve kaydırma pozisyonunu hatırlar.

Bunu uzun bir alışveriş makbuzu olarak düşünmek mümkündür. Makbuz cebinizden daha uzundur; ancak yavaşça çekerek tüm içeriği okuyabilirsiniz. `verticalScroll` da ekrana sığmayan içeriği tam olarak bu şekilde kaydırılabilir kılar.

```kotlin
// MainActivity.kt

package com.example.scrollablemodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ScrollableOrnegi()
        }
    }
}

@Composable
fun UlkeKarti(ulke: String, baskent: String, nufus: String) {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .background(
                color = Color(0xFFF3E5F5),
                shape = RoundedCornerShape(10.dp)
            )
            .padding(14.dp)
    ) {
        Text(
            text = ulke,
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF4A148C)
        )
        Text(
            text = "Başkent: $baskent",
            fontSize = 14.sp,
            color = Color(0xFF424242)
        )
        Text(
            text = "Nüfus: $nufus",
            fontSize = 14.sp,
            color = Color(0xFF424242)
        )
    }
}

@Composable
fun ScrollableOrnegi() {
    // rememberScrollState kaydırma pozisyonunu hatırlar.
    // Bu nesne olmadan verticalScroll çalışmaz.
    val scrollState = rememberScrollState()

    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp)
            // verticalScroll modifier'ı Column'u kaydırılabilir yapar.
            .verticalScroll(scrollState),
        verticalArrangement = Arrangement.spacedBy(12.dp)
    ) {
        Text(
            text = "Ülkeler Listesi",
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold
        )

        UlkeKarti("Türkiye", "Ankara", "85 milyon")
        UlkeKarti("Almanya", "Berlin", "84 milyon")
        UlkeKarti("Fransa", "Paris", "68 milyon")
        UlkeKarti("İtalya", "Roma", "60 milyon")
        UlkeKarti("İspanya", "Madrid", "47 milyon")
        UlkeKarti("Polonya", "Varşova", "38 milyon")
        UlkeKarti("Hollanda", "Amsterdam", "17 milyon")
        UlkeKarti("Belçika", "Brüksel", "11 milyon")
        UlkeKarti("İsveç", "Stockholm", "10 milyon")
        UlkeKarti("Avusturya", "Viyana", "9 milyon")
    }
}

@Preview(showBackground = true)
@Composable
fun ScrollableOrnegiOnizleme() {
    ScrollableOrnegi()
}
```

---

## clickable ve scrollable'ın Önemi

`clickable` ve `scrollable`, bir uygulamayı yalnızca izlenen değil **kullanılan** bir yapıya dönüştürür. Gerçek uygulamalarda neredeyse her ekranda bu iki modifier'dan en az biri yer alır.

Dikkat edilmesi gereken önemli bir nokta şudur: **`LazyColumn` ve `LazyRow` zaten kendi içinde kaydırma desteği taşır.** Bu nedenle `LazyColumn` kullanılan bir `Column`'a ayrıca `verticalScroll` eklenmez; eklenmesi durumunda Compose hata verir. `verticalScroll`, yalnızca sıradan `Column` bileşenleriyle birlikte kullanılır.

---

## Özet

`clickable` modifier'ı herhangi bir bileşeni tıklanabilir yapar ve tıklandığında çalışacak kodu `onClick` bloğu içinde alır. Varsayılan olarak ripple efekti gösterir. `verticalScroll` modifier'ı ise `Column` içindeki uzun içeriklerin ekran dışına taşması durumunda kaydırılabilirlik sağlar; bunun için `rememberScrollState()` ile oluşturulmuş bir `ScrollState` nesnesi gerektirir.



# Modifier Sırasının Önemi (Order Matters)

## Sıra Neden Önemlidir?

Modifier zincirinde her halka, kendinden önceki halkanın bıraktığı bileşen üzerinde çalışır. Bu nedenle iki modifier'ın yerleri değiştirildiğinde ortaya çıkan sonuç da değişir.

Bunu bir ekmek üzerine malzeme koymaya benzetmek mümkündür. Önce tereyağı sürülüp sonra reçel eklenirse reçel tereyağının üzerinde durur. Önce reçel sürülüp sonra tereyağı eklenirse tereyağı reçelin üzerini örter. Malzemeler aynıdır; ancak sıra sonucu tamamen değiştirir. Modifier zinciri de bu şekilde çalışır.

---

## En Sık Karşılaşılan Durum: padding ve background

`padding` ile `background` modifier'larının sırası, Compose'da en çok karıştırılan konulardan biridir. Bu iki modifier'ın yerleri değiştirildiğinde görsel sonuç belirgin biçimde farklılaşır.

```kotlin
// MainActivity.kt

package com.example.modifiersirasi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ModifierSirasiOrnegi()
        }
    }
}

@Composable
fun ModifierSirasiOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp)
    ) {
        Text(
            text = "background → padding",
            fontSize = 13.sp,
            color = Color.Gray
        )
        Spacer(modifier = Modifier.height(4.dp))

        // Durum 1: Önce background, sonra padding
        // Arka plan önce uygulanır; tüm alanı kaplar.
        // Padding sonra gelir ve metni arka planın İÇİNDE iter.
        // Sonuç: renkli alan büyük, metin içeride.
        Text(
            text = "Önce arka plan, sonra boşluk",
            fontSize = 15.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF1565C0))  // ← 1. adım: arka plan
                .padding(20.dp)                  // ← 2. adım: iç boşluk
        )

        Spacer(modifier = Modifier.height(20.dp))

        Text(
            text = "padding → background",
            fontSize = 13.sp,
            color = Color.Gray
        )
        Spacer(modifier = Modifier.height(4.dp))

        // Durum 2: Önce padding, sonra background
        // Padding önce uygulanır; bileşenin dış kenarından boşluk açar.
        // Background sonra gelir ve yalnızca padding'in KALAN alanına uygulanır.
        // Sonuç: renkli alan küçük, çevresinde şeffaf boşluk var.
        Text(
            text = "Önce boşluk, sonra arka plan",
            fontSize = 15.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .padding(20.dp)                  // ← 1. adım: dış boşluk
                .background(Color(0xFF1565C0))   // ← 2. adım: arka plan
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ModifierSirasiOrnegiOnizleme() {
    ModifierSirasiOrnegi()
}
```

İlk durumda arka plan önce gelir; `padding` metni bu renkli alanın içinde iter. Renkli alan büyük görünür. İkinci durumda `padding` önce gelir ve bileşenin dış kenarından boşluk açar; arka plan yalnızca geriye kalan küçük alana uygulanır. Renkli alan belirgin biçimde küçülür.

---

## clickable ve padding Sırası

`clickable` modifier'ı ile `padding` arasındaki sıra da önemli bir fark yaratır. `clickable` modifier'ı bileşene tıklanabilir alan ekler; bu alanın boyutu `padding`'in nereye yazıldığına göre değişir.

```kotlin
// MainActivity.kt

package com.example.clickablesirasi

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ClickableSirasiOrnegi()
        }
    }
}

@Composable
fun ClickableSirasiOrnegi() {
    var tiklamaAlani by remember { mutableStateOf("Henüz tıklanmadı.") }

    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp)
    ) {
        Text(text = tiklamaAlani, fontSize = 15.sp, fontWeight = FontWeight.Bold)
        Spacer(modifier = Modifier.height(16.dp))

        // Durum 1: Önce clickable, sonra padding
        // Tıklanabilir alan metni tam olarak sarar (küçük).
        // Padding ise metnin clickable alanının İÇİNDE boşluk açar.
        // Metnin yanındaki boşluğa tıklamak işe yarar.
        Text(
            text = "clickable → padding (geniş tıklama alanı)",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF2E7D32))
                .clickable { tiklamaAlani = "Geniş alana tıklandı." }
                .padding(20.dp)
        )

        Spacer(modifier = Modifier.height(16.dp))

        // Durum 2: Önce padding, sonra clickable
        // Padding önce dış boşluk açar.
        // clickable yalnızca padding'den sonra kalan iç alana uygulanır.
        // Dış boşluğa (padding alanına) tıklamak işe yaramaz.
        Text(
            text = "padding → clickable (dar tıklama alanı)",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFC62828))
                .padding(20.dp)
                .clickable { tiklamaAlani = "Dar alana tıklandı." }
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ClickableSirasiOrnegiOnizleme() {
    ClickableSirasiOrnegi()
}
```

İlk durumda `clickable` önce geldiği için tıklanabilir alan `padding` dahil tüm bileşeni kaplar; metnin yanındaki boşluğa tıklamak da tıklama olayını tetikler. İkinci durumda `padding` önce geldiği için tıklanabilir alan yalnızca metnin kendisini kapsar; dış boşluğa tıklamak hiçbir şey yapmaz.

---

## Modifier Sırasını Akılda Tutmanın Yolu

Modifier zinciri **yukarıdan aşağıya, sırayla** okunarak yorumlanır. Her modifier kendisinden önceki modifier'ın sonucuna uygulanır. Şu soru sorulabilir: "Bu modifier, kendinden önceki modifier'ın bıraktığı bileşene ne yapıyor?"

Bunu bir boya-zımplama işlemine benzetmek mümkündür. Önce zımparalayıp sonra boyamak ile önce boyayıp sonra zımparalamak çok farklı sonuçlar verir. Hangi işlemin önce yapıldığı her şeyi değiştirir.

```kotlin
// MainActivity.kt

package com.example.siraokuması

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.shadow
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            Column(
                modifier = Modifier
                    .background(Color(0xFFF5F5F5))
                    .padding(20.dp)
            ) {
                DogruSira()
                Spacer(modifier = Modifier.height(24.dp))
                YanlisSira()
            }
        }
    }
}

@Composable
fun DogruSira() {
    Text(
        text = "Doğru sıra: shadow → background → padding",
        fontSize = 13.sp,
        color = Color.Gray
    )
    Spacer(modifier = Modifier.height(6.dp))

    // Doğru sıra:
    // 1. shadow: gölge, şekli tanır (background'dan önce gelmeli)
    // 2. background: renkli arka plan, yuvarlak köşeli
    // 3. border: kenarlık çizgisi
    // 4. padding: iç boşluk (en sona gelir)
    Text(
        text = "Doğru sıralanmış kart",
        fontSize = 15.sp,
        fontWeight = FontWeight.Medium,
        modifier = Modifier
            .fillMaxWidth()
            .shadow(6.dp, RoundedCornerShape(12.dp))
            .background(Color.White, RoundedCornerShape(12.dp))
            .border(1.dp, Color(0xFFBBDEFB), RoundedCornerShape(12.dp))
            .padding(16.dp)
    )
}

@Composable
fun YanlisSira() {
    Text(
        text = "Yanlış sıra: padding → shadow → background",
        fontSize = 13.sp,
        color = Color.Gray
    )
    Spacer(modifier = Modifier.height(6.dp))

    // Yanlış sıra:
    // padding önce geldiği için shadow ve background
    // küçülmüş alana uygulanır; gölge ve arka plan
    // beklenenden küçük görünür.
    Text(
        text = "Yanlış sıralanmış kart",
        fontSize = 15.sp,
        fontWeight = FontWeight.Medium,
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp)
            .shadow(6.dp, RoundedCornerShape(12.dp))
            .background(Color.White, RoundedCornerShape(12.dp))
    )
}

@Preview(showBackground = true)
@Composable
fun SiraOkumasiOnizleme() {
    Column(
        modifier = Modifier
            .background(Color(0xFFF5F5F5))
            .padding(20.dp)
    ) {
        DogruSira()
        Spacer(modifier = Modifier.height(24.dp))
        YanlisSira()
    }
}
```

---

## Genel Kural: Önerilen Sıralama

Tüm modifier'lar bir arada kullanıldığında aşağıdaki sıralama büyük çoğunlukla doğru sonucu verir:

| Sıra | Modifier | Açıklama |
|---|---|---|
| 1 | `fillMaxWidth()` / `size()` | Önce boyut belirlenir |
| 2 | `shadow()` | Gölge, background'dan önce gelmelidir |
| 3 | `clip()` | Şekle kırpma |
| 4 | `background()` | Arka plan rengi |
| 5 | `border()` | Kenarlık çizgisi |
| 6 | `clickable()` | Tıklanabilir alan geniş olsun isteniyorsa |
| 7 | `padding()` | İç boşluk en sona gelir |

Bu sıralama her durumda mutlak bir kural değildir; farklı tasarım ihtiyaçlarında sıra bilinçli olarak değiştirilebilir. Önemli olan, sıranın sonucu etkilediğinin farkında olmak ve her modifier'ın kendinden önce gelenden ne aldığını düşünmektir.

---

## Özet

Modifier zincirinde sıra, sonucu doğrudan değiştirir. Her modifier kendisinden önceki modifier'ın bıraktığı bileşen üzerinde çalışır. `background` ve `padding` arasındaki sıra renkli alanın boyutunu, `clickable` ve `padding` arasındaki sıra ise tıklanabilir alanın genişliğini belirler. Zincir her zaman yukarıdan aşağıya okunarak yorumlanmalıdır.



# verticalArrangement: Top, Bottom, Center, SpaceBetween, SpaceEvenly

## verticalArrangement Nedir?

`Column` içindeki bileşenler varsayılan olarak birbirine yapışık şekilde en üstten başlar. Ancak çoğu zaman bu bileşenlerin ekran üzerinde farklı biçimlerde dağıtılması gerekir. Kimi zaman ortada toplanmaları, kimi zaman eşit aralıklarla yayılmaları istenebilir.

`verticalArrangement` parametresi, `Column` içindeki bileşenlerin **dikey eksende nasıl dağıtılacağını** belirler.

Bunu bir asansör kabinine yerleştirilen kutulara benzetmek mümkündür. Kutular kabinin içine konulduğunda hepsini üst üste en alta yığmak, eşit aralıklarla dağıtmak ya da tam ortada toplamak mümkündür. `verticalArrangement` da `Column` içindeki bileşenlere tam olarak bu düzeni uygular.

---

## Arrangement.Top: Üstten Başla

`Arrangement.Top`, `Column`'un varsayılan davranışıdır. Bileşenler üstten başlar ve birbirinin hemen altına yapışık olarak dizilir. Aralarında hiç boşluk bulunmaz.

```kotlin
// MainActivity.kt

package com.example.arrangementtop

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ArrangementTopOrnegi()
        }
    }
}

// Görsel karşılaştırma için renkli kutu
@Composable
fun DuzenlemeKutusu(metin: String, renk: Color) {
    Text(
        text = metin,
        fontSize = 14.sp,
        fontWeight = FontWeight.Medium,
        color = Color.White,
        textAlign = TextAlign.Center,
        modifier = Modifier
            .fillMaxWidth()
            .background(renk)
            .padding(12.dp)
    )
}

@Composable
fun ArrangementTopOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        // Arrangement.Top varsayılan değerdir;
        // yazılmasa da aynı sonucu verir.
        verticalArrangement = Arrangement.Top
    ) {
        DuzenlemeKutusu("Birinci", Color(0xFF1565C0))
        DuzenlemeKutusu("İkinci", Color(0xFF1976D2))
        DuzenlemeKutusu("Üçüncü", Color(0xFF1E88E5))
    }
}

@Preview(showBackground = true)
@Composable
fun ArrangementTopOnizleme() {
    ArrangementTopOrnegi()
}
```

---

## Arrangement.Bottom: Alta Hizala

`Arrangement.Bottom`, bileşenleri `Column`'un en altına yerleştirir. Bileşenler birbirinin hemen üstüne yapışık olarak alta yığılır; üstte boş alan kalır.

Bunu bir bardağın dibine çöken taneciklere benzetmek mümkündür. Tüm içerik en alta çöker, üst kısım boş kalır.

```kotlin
// MainActivity.kt

package com.example.arrangementbottom

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ArrangementBottomOrnegi()
        }
    }
}

@Composable
fun ArrangementBottomOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.Bottom
    ) {
        Text(
            text = "Birinci",
            fontSize = 14.sp,
            fontWeight = FontWeight.Medium,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF2E7D32))
                .padding(12.dp)
        )
        Text(
            text = "İkinci",
            fontSize = 14.sp,
            fontWeight = FontWeight.Medium,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF388E3C))
                .padding(12.dp)
        )
        Text(
            text = "Üçüncü",
            fontSize = 14.sp,
            fontWeight = FontWeight.Medium,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF43A047))
                .padding(12.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ArrangementBottomOnizleme() {
    ArrangementBottomOrnegi()
}
```

---

## Arrangement.Center: Ortala

`Arrangement.Center`, bileşenleri `Column`'un tam dikey ortasında toplar. Bileşenler birbirinin hemen altına yapışık durur; ancak bu yapışık grup ekranın ortasına hizalanır.

Bunu bir tablo üzerindeki bir kâğıda yazılmış notları ortalamayla benzetmek mümkündür. Notlar birbirine yapışık kalır; ancak tüm grup kâğıdın tam ortasına yerleştirilir.

```kotlin
// MainActivity.kt

package com.example.arrangementcenter

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ArrangementCenterOrnegi()
        }
    }
}

@Composable
fun ArrangementCenterOrnegi() {
    // verticalArrangement Center + horizontalAlignment Center
    // bileşenleri tam ekran ortasına taşır.
    // Giriş ekranları ve boş durum ekranlarında sıkça kullanılır.
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.Center,
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(
            text = "🎯",
            fontSize = 48.sp
        )
        Text(
            text = "Hedefe Ulaşıldı",
            fontSize = 22.sp,
            fontWeight = FontWeight.Bold
        )
        Text(
            text = "Tüm görevler tamamlandı.",
            fontSize = 15.sp,
            color = Color.Gray
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ArrangementCenterOnizleme() {
    ArrangementCenterOrnegi()
}
```

---

## Arrangement.SpaceBetween: Aralarına Eşit Boşluk

`Arrangement.SpaceBetween`, **ilk bileşeni en üste, son bileşeni en alta** yerleştirir. Aradaki bileşenler ise kalan boşluğu eşit paylaşacak şekilde dağıtılır. İlk ve son bileşenlerin dışında herhangi bir boşluk kalmaz.

Bunu bir kapı çerçevesine benzetmek mümkündür. İlk tahta çerçevenin üstüne, son tahta çerçevenin altına çakılır. Aradaki tahtalar kalan mesafeyi eşit bölerek yerleştirilir. Çerçevenin dışında boşluk kalmaz.

```kotlin
// MainActivity.kt

package com.example.arrangementspacebetween

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SpaceBetweenOrnegi()
        }
    }
}

@Composable
fun SpaceBetweenOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.SpaceBetween
    ) {
        // İlk bileşen en üste yapışır
        Text(
            text = "En üst — Başlık",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFC62828))
                .padding(12.dp)
        )
        // Orta bileşen eşit bölünmüş alanın ortasına gelir
        Text(
            text = "Orta — İçerik",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFE53935))
                .padding(12.dp)
        )
        // Son bileşen en alta yapışır
        Text(
            text = "En alt — Dipnot",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFEF5350))
                .padding(12.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun SpaceBetweenOnizleme() {
    SpaceBetweenOrnegi()
}
```

---

## Arrangement.SpaceEvenly: Her Boşluk Eşit

`Arrangement.SpaceEvenly`, tüm boşlukları —bileşenler arasındaki ve kenarlardaki— **tamamen eşit** dağıtır. `SpaceBetween`'den farkı şudur: `SpaceBetween`'de kenarlarda boşluk yoktur; `SpaceEvenly`'de ise kenarlar da dahil olmak üzere her boşluk eşittir.

Bunu bir sergi salonundaki tablolara benzetmek mümkündür. Tablolar duvardan eşit mesafede başlar, tablolar arasındaki mesafeler de bu uzaklığa eşittir. Hiçbir tablo duvara yapışık değildir, hiçbiri diğerinden daha fazla boşluğa sahip değildir.

```kotlin
// MainActivity.kt

package com.example.arrangementspaceevenly

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SpaceEvenlyOrnegi()
        }
    }
}

@Composable
fun SpaceEvenlyOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(horizontal = 16.dp),
        verticalArrangement = Arrangement.SpaceEvenly
    ) {
        Text(
            text = "Adım 1",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF6A1B9A))
                .padding(12.dp)
        )
        Text(
            text = "Adım 2",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF7B1FA2))
                .padding(12.dp)
        )
        Text(
            text = "Adım 3",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF8E24AA))
                .padding(12.dp)
        )
        Text(
            text = "Adım 4",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF9C27B0))
                .padding(12.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun SpaceEvenlyOnizleme() {
    SpaceEvenlyOrnegi()
}
```

---

## Beş Değerin Karşılaştırması

Beş değer arasındaki farkı tek bir ekranda görmek, hangi değerin ne zaman kullanılacağını netleştirir:

| Değer | Bileşenler nerede başlar? | Kenar boşluğu var mı? |
|---|---|---|
| `Top` | En üstten, yapışık | Yok |
| `Bottom` | En alttan, yapışık | Yok |
| `Center` | Ortada, yapışık | Üstte ve altta eşit |
| `SpaceBetween` | İlk üstte, son altta | Yok |
| `SpaceEvenly` | Her yerde eşit dağılım | Var (tüm boşluklar eşit) |

---

## Özet

`verticalArrangement`, `Column` içindeki bileşenlerin dikey eksende nasıl dağıtılacağını belirler. `Top` ve `Bottom` bileşenleri ilgili kenara yapıştırır. `Center` onları ortalar. `SpaceBetween` ilk ve son bileşeni kenarlara sabitleyip aralarını eşit böler. `SpaceEvenly` ise kenarlar dahil tüm boşlukları eşit dağıtır. Doğru değerin seçilmesi, ekranın hem düzenli hem de dengeli görünmesini sağlar.




# horizontalAlignment: Start, CenterHorizontally, End

## horizontalAlignment Nedir?

Bir önceki derste `verticalArrangement`'ın `Column` içindeki bileşenleri **dikey eksende** nasıl dağıttığı ele alındı. `horizontalAlignment` ise `Column` içindeki bileşenlerin **yatay eksende** nereye hizalanacağını belirler.

Varsayılan olarak `Column` içindeki bileşenler sola hizalanır. Ancak bir başlığın ortada, bir düğmenin sağda ya da tüm içeriğin tam ortada durması gerekebilir. `horizontalAlignment` bu hizalama kararını verir.

Bunu bir not defterindeki yazılara benzetmek mümkündür. Bazı defterler yazıyı sola hizalar, bazı kitaplar başlıkları ortalar, bazı belgeler ise imzayı sağa koyar. `horizontalAlignment` da bileşenlere tam olarak bu hizalama kararını uygular.

---

## Alignment.Start: Sola Hizala

`Alignment.Start`, `Column`'un varsayılan davranışıdır. Bileşenler `Column`'un sol kenarına hizalanır. Yazılmasa da aynı sonucu verir; ancak kodun okunabilirliği açısından açıkça belirtmek iyi bir alışkanlıktır.

```kotlin
// MainActivity.kt

package com.example.alignmentstart

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.wrapContentWidth
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AlignmentStartOrnegi()
        }
    }
}

@Composable
fun AlignmentStartOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.spacedBy(12.dp),
        // Alignment.Start: tüm bileşenler sola hizalanır (varsayılan)
        horizontalAlignment = Alignment.Start
    ) {
        Text(
            text = "Kısa metin",
            fontSize = 15.sp,
            color = Color.White,
            modifier = Modifier
                .background(Color(0xFF1565C0))
                .padding(horizontal = 12.dp, vertical = 8.dp)
        )
        Text(
            text = "Biraz daha uzun bir metin",
            fontSize = 15.sp,
            color = Color.White,
            modifier = Modifier
                .background(Color(0xFF1565C0))
                .padding(horizontal = 12.dp, vertical = 8.dp)
        )
        Text(
            text = "En uzun metin burası",
            fontSize = 15.sp,
            color = Color.White,
            modifier = Modifier
                .background(Color(0xFF1565C0))
                .padding(horizontal = 12.dp, vertical = 8.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun AlignmentStartOnizleme() {
    AlignmentStartOrnegi()
}
```

Her bileşen farklı genişliktedir; ancak hepsi sol kenara hizalıdır. Birinin genişliği değiştiğinde diğerlerinin konumu etkilenmez.

---

## Alignment.CenterHorizontally: Yatay Ortala

`Alignment.CenterHorizontally`, bileşenleri `Column`'un yatay merkezine hizalar. Bileşenler ne kadar geniş ya da dar olursa olsun hepsi `Column`'un tam ortasına yerleşir.

Bunu bir fotoğraf albümüne benzetmek mümkündür. Albümdeki her fotoğraf sayfanın ortasına yapıştırılmıştır. Fotoğrafların boyutu birbirinden farklı olsa da hepsi aynı merkez noktasına hizalıdır.

`Alignment.CenterHorizontally`, giriş ekranları, boş durum ekranları ve profil sayfaları gibi içeriğin ortada toplanmasının istendiği ekranlarda sıkça kullanılır.

```kotlin
// MainActivity.kt

package com.example.alignmentcenter

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.AccountCircle
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AlignmentCenterOrnegi()
        }
    }
}

@Composable
fun AlignmentCenterOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.Center,
        // Tüm bileşenler yatay olarak ortaya hizalanır
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        // İkon — ortalanmış
        Icon(
            imageVector = Icons.Filled.AccountCircle,
            contentDescription = null,
            tint = Color(0xFF1565C0),
            modifier = Modifier.size(80.dp)
        )

        Spacer(modifier = Modifier.height(12.dp))

        // İsim — ortalanmış
        Text(
            text = "Ali Yılmaz",
            fontSize = 22.sp,
            fontWeight = FontWeight.Bold
        )

        Spacer(modifier = Modifier.height(4.dp))

        // Unvan — ortalanmış
        Text(
            text = "Android Geliştirici",
            fontSize = 15.sp,
            color = Color.Gray
        )

        Spacer(modifier = Modifier.height(4.dp))

        // Şehir — ortalanmış
        Text(
            text = "Ankara, Türkiye",
            fontSize = 14.sp,
            color = Color(0xFF1565C0)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun AlignmentCenterOnizleme() {
    AlignmentCenterOrnegi()
}
```

Bileşenler farklı genişliklere sahiptir: ikon, isim ve unvan metninin genişlikleri birbirinden farklıdır. Ancak `Alignment.CenterHorizontally` sayesinde hepsi `Column`'un tam ortasına hizalanmıştır.

---

## Alignment.End: Sağa Hizala

`Alignment.End`, bileşenleri `Column`'un sağ kenarına hizalar. Türkiye'de okunan dil soldan sağa ilerlediği için sağa hizalama genellikle tarihler, saatler, fiyatlar ve onay işaretleri gibi ikincil bilgiler için tercih edilir.

Bunu bir fatura düzenine benzetmek mümkündür. Faturanın sağ sütununda yer alan tutarlar her zaman sağa hizalanmıştır; böylece rakamların ondalık noktaları birbirine dikey olarak hizalanır ve okunması kolaylaşır.

```kotlin
// MainActivity.kt

package com.example.alignmentend

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AlignmentEndOrnegi()
        }
    }
}

@Composable
fun AlignmentEndOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.spacedBy(8.dp),
        // Tüm bileşenler sağ kenara hizalanır
        horizontalAlignment = Alignment.End
    ) {
        Text(
            text = "Sipariş Özeti",
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF1A237E)
        )

        HorizontalDivider()

        Text(
            text = "Ürün × 2",
            fontSize = 14.sp,
            color = Color.Gray
        )
        Text(
            text = "₺ 259,90",
            fontSize = 16.sp,
            fontWeight = FontWeight.Medium
        )

        Text(
            text = "Kargo",
            fontSize = 14.sp,
            color = Color.Gray
        )
        Text(
            text = "₺ 29,90",
            fontSize = 16.sp,
            fontWeight = FontWeight.Medium
        )

        HorizontalDivider()

        Text(
            text = "Toplam",
            fontSize = 14.sp,
            color = Color.Gray
        )
        Text(
            text = "₺ 289,80",
            fontSize = 18.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF1565C0)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun AlignmentEndOnizleme() {
    AlignmentEndOrnegi()
}
```

---

## Bileşen Bazında Hizalama: align() Modifier'ı

`horizontalAlignment` parametresi `Column` içindeki **tüm** bileşenlere aynı hizalamayı uygular. Ancak yalnızca belirli bir bileşenin farklı hizalanması gerekiyorsa `Modifier.align()` kullanılır. Bu modifier, `Column` içinde yalnızca uygulandığı bileşeni etkiler; diğer bileşenler `horizontalAlignment`'a göre hizalanmaya devam eder.

Bunu bir sınıftaki oturma düzenine benzetmek mümkündür. Tüm öğrenciler sıralar boyunca solda oturur; ancak bir öğrenci ayrıcalıklı olarak ortaya alınmıştır. `align()` modifier'ı tam olarak bu "ayrıcalıklı konumlandırmayı" sağlar.

```kotlin
// MainActivity.kt

package com.example.alignmodifier

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AlignModifierOrnegi()
        }
    }
}

@Composable
fun AlignModifierOrnegi() {
    // Column genel olarak sola hizalıdır
    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(16.dp),
        verticalArrangement = Arrangement.spacedBy(10.dp),
        horizontalAlignment = Alignment.Start  // Genel kural: sola hizala
    ) {
        // Bu bileşen genel kurala uyar: sola hizalı
        Text(
            text = "Sola hizalı (genel kural)",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .background(Color(0xFF1565C0))
                .padding(10.dp)
        )

        // Bu bileşen genel kuralı geçersiz kılar: ortaya hizalı
        Text(
            text = "Ortaya hizalı (align ile)",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .align(Alignment.CenterHorizontally)  // ← yalnızca bu bileşen
                .background(Color(0xFF2E7D32))
                .padding(10.dp)
        )

        // Bu bileşen genel kuralı geçersiz kılar: sağa hizalı
        Text(
            text = "Sağa hizalı (align ile)",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .align(Alignment.End)  // ← yalnızca bu bileşen
                .background(Color(0xFFC62828))
                .padding(10.dp)
        )

        // Bu bileşen tekrar genel kurala döner: sola hizalı
        Text(
            text = "Sola hizalı (genel kural)",
            fontSize = 14.sp,
            color = Color.White,
            modifier = Modifier
                .background(Color(0xFF1565C0))
                .padding(10.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun AlignModifierOnizleme() {
    AlignModifierOrnegi()
}
```

---

## Üç Değerin Karşılaştırması

| Değer | Bileşenler nerede durur? | Yaygın Kullanım Yeri |
|---|---|---|
| `Alignment.Start` | Sol kenara hizalı (varsayılan) | Metin listeleri, form alanları |
| `Alignment.CenterHorizontally` | Yatay merkeze hizalı | Profil sayfaları, giriş ekranları |
| `Alignment.End` | Sağ kenara hizalı | Fiyatlar, tarihler, onay ikonları |

---

## Özet

`horizontalAlignment`, `Column` içindeki bileşenlerin yatay eksende nereye hizalanacağını belirler. `Alignment.Start` sola, `Alignment.CenterHorizontally` ortaya, `Alignment.End` sağa hizalar. Tüm bileşenler için geçerli olan bu genel kural, belirli bileşenlerde `Modifier.align()` kullanılarak ayrı ayrı geçersiz kılınabilir. Bu esneklik, aynı `Column` içinde farklı hizalamaların bir arada kullanılmasını mümkün kılar.



# horizontalArrangement ve verticalAlignment (Row için)

## Row'da Düzenleme Nasıl Çalışır?

Bir önceki derste `Column`'un iki parametresi olan `verticalArrangement` ve `horizontalAlignment` ele alındı. `Row` için bu iki parametrenin eksenleri tam tersine döner: `Row` bileşenleri yatay olarak dizdiği için dağılım yatay eksende, hizalama ise dikey eksende yapılır.

Bunu bir müzik notası satırına benzetmek mümkündür. Notalar satır boyunca soldan sağa dizilir; bu dizilim `horizontalArrangement`'tır. Notaların çizgiye göre üstte mi, tam ortada mı yoksa altta mı durduğu ise `verticalAlignment`'tır.

| Parametre | Hangi eksen? | Hangi layout? |
|---|---|---|
| `verticalArrangement` | Dikey | `Column` |
| `horizontalAlignment` | Yatay | `Column` |
| `horizontalArrangement` | Yatay | `Row` |
| `verticalAlignment` | Dikey | `Row` |

---

## horizontalArrangement: Yatay Dağılım

`Row` içindeki bileşenlerin yatay eksende nasıl dağıtılacağını `horizontalArrangement` belirler. Bu parametre, `Column`'daki `verticalArrangement`'ın yatay eksendeki karşılığıdır; aynı `Arrangement` değerleri kullanılır.

```kotlin
// MainActivity.kt

package com.example.horizontalarrangement

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            HorizontalArrangementOrnegi()
        }
    }
}

// Görsel karşılaştırma için renkli küçük kutu
@Composable
fun RowKutusu(metin: String, renk: Color) {
    Text(
        text = metin,
        fontSize = 13.sp,
        fontWeight = FontWeight.Bold,
        color = Color.White,
        textAlign = TextAlign.Center,
        modifier = Modifier
            .background(renk)
            .padding(horizontal = 10.dp, vertical = 8.dp)
    )
}

// Düzenleme etiketiyle birlikte Row gösteren yardımcı composable
@Composable
fun EtiketliRow(etiket: String, icerik: @Composable () -> Unit) {
    Text(text = etiket, fontSize = 12.sp, color = Color.Gray)
    Spacer(modifier = Modifier.height(4.dp))
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .background(Color(0xFFF5F5F5))
            .padding(4.dp),
        horizontalArrangement = when (etiket) {
            "Start" -> Arrangement.Start
            "Center" -> Arrangement.Center
            "End" -> Arrangement.End
            "SpaceBetween" -> Arrangement.SpaceBetween
            "SpaceEvenly" -> Arrangement.SpaceEvenly
            else -> Arrangement.Start
        }
    ) {
        icerik()
    }
    Spacer(modifier = Modifier.height(12.dp))
}

@Composable
fun HorizontalArrangementOrnegi() {
    val mavi = Color(0xFF1565C0)
    val yesil = Color(0xFF2E7D32)
    val kirmizi = Color(0xFFC62828)

    Column(modifier = Modifier.padding(16.dp)) {

        Text(
            text = "horizontalArrangement Değerleri",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )
        Spacer(modifier = Modifier.height(14.dp))

        // Start: bileşenler sola yapışık
        Text(text = "Start", fontSize = 12.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(4.dp),
            horizontalArrangement = Arrangement.Start
        ) {
            RowKutusu("A", mavi)
            RowKutusu("B", mavi)
            RowKutusu("C", mavi)
        }
        Spacer(modifier = Modifier.height(10.dp))

        // Center: bileşenler ortada toplanır
        Text(text = "Center", fontSize = 12.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(4.dp),
            horizontalArrangement = Arrangement.Center
        ) {
            RowKutusu("A", yesil)
            RowKutusu("B", yesil)
            RowKutusu("C", yesil)
        }
        Spacer(modifier = Modifier.height(10.dp))

        // End: bileşenler sağa yapışık
        Text(text = "End", fontSize = 12.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(4.dp),
            horizontalArrangement = Arrangement.End
        ) {
            RowKutusu("A", kirmizi)
            RowKutusu("B", kirmizi)
            RowKutusu("C", kirmizi)
        }
        Spacer(modifier = Modifier.height(10.dp))

        // SpaceBetween: ilk sola, son sağa, aralar eşit
        Text(text = "SpaceBetween", fontSize = 12.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(4.dp),
            horizontalArrangement = Arrangement.SpaceBetween
        ) {
            RowKutusu("A", Color(0xFF6A1B9A))
            RowKutusu("B", Color(0xFF6A1B9A))
            RowKutusu("C", Color(0xFF6A1B9A))
        }
        Spacer(modifier = Modifier.height(10.dp))

        // SpaceEvenly: tüm boşluklar eşit (kenarlar dahil)
        Text(text = "SpaceEvenly", fontSize = 12.sp, color = Color.Gray)
        Spacer(modifier = Modifier.height(4.dp))
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(4.dp),
            horizontalArrangement = Arrangement.SpaceEvenly
        ) {
            RowKutusu("A", Color(0xFFE65100))
            RowKutusu("B", Color(0xFFE65100))
            RowKutusu("C", Color(0xFFE65100))
        }
    }
}

@Preview(showBackground = true)
@Composable
fun HorizontalArrangementOnizleme() {
    HorizontalArrangementOrnegi()
}
```

---

## verticalAlignment: Dikey Hizalama

`Row` içindeki bileşenler farklı yüksekliklere sahip olabilir. Büyük bir ikon ile küçük bir metin yan yana durduğunda dikey olarak nasıl hizalanacakları `verticalAlignment` ile belirlenir.

Bunu bir raf üzerindeki farklı boyutlardaki kitaplara benzetmek mümkündür. Kalın bir ansiklopedi ile ince bir not defteri yan yana durduğunda ikisi üst kenara mı, alt kenara mı yoksa ortalarına mı hizalanacak? `verticalAlignment` tam olarak bu kararı verir.

`Row` için kullanılan `verticalAlignment` değerleri şunlardır:

| Değer | Açıklama |
|---|---|
| `Alignment.Top` | Bileşenler üst kenara hizalanır |
| `Alignment.CenterVertically` | Bileşenler dikey merkeze hizalanır |
| `Alignment.Bottom` | Bileşenler alt kenara hizalanır |

```kotlin
// MainActivity.kt

package com.example.verticalalignment

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            VerticalAlignmentOrnegi()
        }
    }
}

// Farklı boyutlarda üç metin içeren yardımcı composable
@Composable
fun FarkliYukseklikler() {
    Text(
        text = "Büyük",
        fontSize = 28.sp,
        fontWeight = FontWeight.Bold,
        color = Color.White,
        modifier = Modifier
            .background(Color(0xFF1565C0))
            .padding(8.dp)
    )
    Spacer(modifier = Modifier.width(8.dp))
    Text(
        text = "Orta",
        fontSize = 18.sp,
        color = Color.White,
        modifier = Modifier
            .background(Color(0xFF2E7D32))
            .padding(8.dp)
    )
    Spacer(modifier = Modifier.width(8.dp))
    Text(
        text = "Küçük",
        fontSize = 12.sp,
        color = Color.White,
        modifier = Modifier
            .background(Color(0xFFC62828))
            .padding(8.dp)
    )
}

@Composable
fun VerticalAlignmentOrnegi() {
    Column(
        modifier = Modifier
            .padding(16.dp)
            .fillMaxWidth(),
        verticalArrangement = Arrangement.spacedBy(16.dp)
    ) {
        Text(
            text = "verticalAlignment Değerleri",
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )

        // Alignment.Top: tüm bileşenler üst kenara hizalanır
        Text(text = "Alignment.Top", fontSize = 12.sp, color = Color.Gray)
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(8.dp),
            verticalAlignment = Alignment.Top
        ) {
            FarkliYukseklikler()
        }

        // Alignment.CenterVertically: tüm bileşenler dikey ortaya hizalanır
        Text(text = "Alignment.CenterVertically", fontSize = 12.sp, color = Color.Gray)
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(8.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            FarkliYukseklikler()
        }

        // Alignment.Bottom: tüm bileşenler alt kenara hizalanır
        Text(text = "Alignment.Bottom", fontSize = 12.sp, color = Color.Gray)
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFFF5F5F5))
                .padding(8.dp),
            verticalAlignment = Alignment.Bottom
        ) {
            FarkliYukseklikler()
        }
    }
}

@Preview(showBackground = true)
@Composable
fun VerticalAlignmentOnizleme() {
    VerticalAlignmentOrnegi()
}
```

Üç örnekte de bileşenler aynıdır; yalnızca `verticalAlignment` değeri değişmektedir. Bu fark, özellikle farklı boyuttaki bileşenler yan yana geldiğinde belirgin biçimde ortaya çıkar.

---

## İkisini Birlikte Kullanmak

Gerçek uygulamalarda `horizontalArrangement` ve `verticalAlignment` çoğunlukla birlikte kullanılır. Aşağıdaki örnek, bu iki parametrenin birlikte nasıl çalıştığını tipik bir uygulama satırı üzerinde göstermektedir.

```kotlin
// MainActivity.kt

package com.example.arrangementalignmentbirlikte

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Notifications
import androidx.compose.material.icons.filled.Search
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.shadow
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            UygulamaUstCubugu()
        }
    }
}

@Composable
fun UygulamaUstCubugu() {
    Column(
        modifier = Modifier
            .background(Color(0xFFF5F5F5))
            .padding(16.dp)
    ) {
        // Üst çubuk: sol tarafta başlık, sağ tarafta ikonlar
        // horizontalArrangement = SpaceBetween: başlık sola, ikonlar sağa
        // verticalAlignment = CenterVertically: hepsi dikey ortada
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .shadow(4.dp, RoundedCornerShape(12.dp))
                .background(Color.White, RoundedCornerShape(12.dp))
                .padding(horizontal = 16.dp, vertical = 12.dp),
            horizontalArrangement = Arrangement.SpaceBetween,
            verticalAlignment = Alignment.CenterVertically
        ) {
            // Sol taraf: uygulama başlığı
            Column {
                Text(
                    text = "Merhaba,",
                    fontSize = 13.sp,
                    color = Color.Gray
                )
                Text(
                    text = "Ali Yılmaz",
                    fontSize = 18.sp,
                    fontWeight = FontWeight.Bold,
                    color = Color(0xFF1A237E)
                )
            }

            // Sağ taraf: iki ikon yan yana
            // Bu iç Row da kendi horizontalArrangement'ına sahiptir
            Row(
                horizontalArrangement = Arrangement.spacedBy(12.dp),
                verticalAlignment = Alignment.CenterVertically
            ) {
                Icon(
                    imageVector = Icons.Filled.Search,
                    contentDescription = "Ara",
                    tint = Color(0xFF1565C0),
                    modifier = Modifier.size(24.dp)
                )
                Icon(
                    imageVector = Icons.Filled.Notifications,
                    contentDescription = "Bildirimler",
                    tint = Color(0xFF1565C0),
                    modifier = Modifier.size(24.dp)
                )
            }
        }

        Spacer(modifier = Modifier.height(16.dp))

        // Alt kısım: üç istatistik kutusu eşit dağılmış
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceEvenly,
            verticalAlignment = Alignment.CenterVertically
        ) {
            IstatistikKutusu("128", "Gönderi", Color(0xFF1565C0))
            IstatistikKutusu("4.2K", "Takipçi", Color(0xFF2E7D32))
            IstatistikKutusu("312", "Takip", Color(0xFFC62828))
        }
    }
}

@Composable
fun IstatistikKutusu(sayi: String, etiket: String, renk: Color) {
    Column(
        modifier = Modifier
            .shadow(2.dp, RoundedCornerShape(10.dp))
            .background(Color.White, RoundedCornerShape(10.dp))
            .padding(horizontal = 16.dp, vertical = 12.dp),
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(
            text = sayi,
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold,
            color = renk
        )
        Text(
            text = etiket,
            fontSize = 12.sp,
            color = Color.Gray
        )
    }
}

@Preview(showBackground = true)
@Composable
fun UygulamaUstCubuguOnizleme() {
    UygulamaUstCubugu()
}
```

Üst çubukta `horizontalArrangement = Arrangement.SpaceBetween` başlığı sola, ikonları sağa yaslar. `verticalAlignment = Alignment.CenterVertically` ise farklı yükseklikteki başlık bloğu ile ikon bloğunu dikey olarak ortalar. Alt kısımdaki istatistik kutularında ise `SpaceEvenly` üç kutuyu eşit boşluklarla dağıtır.

---

## Özet

`Row` için `horizontalArrangement`, bileşenlerin yatay eksendeki dağılımını; `verticalAlignment` ise dikey eksendeki hizalanmasını belirler. `horizontalArrangement` için `Start`, `Center`, `End`, `SpaceBetween` ve `SpaceEvenly` değerleri kullanılır. `verticalAlignment` için ise `Top`, `CenterVertically` ve `Bottom` değerleri mevcuttur. Bu iki parametre birlikte kullanıldığında `Row` içindeki bileşenler tam istenen konuma yerleştirilebilir.



# Weight Modifier ile Esnek Boyutlandırma

## weight Nedir?

`Row` ya da `Column` içindeki bileşenlere sabit bir boyut vermek yerine, kalan boşluğu bileşenler arasında **oransal olarak paylaştırmak** mümkündür. Bu paylaştırma işlemi `Modifier.weight()` ile yapılır.

Bunu bir pastayı paylaştırmaya benzetmek mümkündür. Pasta üç kişiye paylaştırılacaksa herkese eşit dilim verilebilir; ya da birine çift dilim, diğerlerine tek dilim verilebilir. `weight()` modifier'ı da kalan alanı bileşenler arasında tam olarak bu şekilde paylaştırır. Büyük `weight` değeri daha büyük dilim, küçük `weight` değeri daha küçük dilim anlamına gelir.

`weight()` modifier'ı yalnızca `Row` ve `Column` içinde çalışır; bu iki layout dışında kullanılmaz.

---

## weight'in Temel Mantığı

`weight()` içine verilen sayı, o bileşenin toplam ağırlık içindeki **payını** belirler. Örneğin iki bileşen `weight(1f)` ve `weight(1f)` değerlerini taşıyorsa toplam ağırlık 2'dir ve her bileşen alanın yarısını kaplar. Biri `weight(2f)`, diğeri `weight(1f)` değerini taşıyorsa toplam ağırlık 3'tür; biri alanın üçte ikisini, diğeri üçte birini kaplar.

Bunu bir cetvel üzerinde düşünmek mümkündür. 30 cm'lik bir cetveli iki parçaya bölerken birine 10 cm, diğerine 20 cm vermek isteniyorsa ağırlıklar 1 ve 2 olur. Toplam 3 birim içinde 10 cm 1 birime, 20 cm 2 birime karşılık gelir.

```kotlin
// MainActivity.kt

package com.example.weighttemel

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            WeightTemelOrnegi()
        }
    }
}

@Composable
fun WeightTemelOrnegi() {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp)
    ) {
        Text(
            text = "Eşit Paylaşım (1f + 1f)",
            fontSize = 13.sp,
            color = Color.Gray
        )
        Spacer(modifier = Modifier.height(4.dp))

        // İki bileşen eşit ağırlık: her biri %50 genişlik kaplar
        Row(modifier = Modifier.fillMaxWidth()) {
            Text(
                text = "Sol (%50)",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                textAlign = TextAlign.Center,
                modifier = Modifier
                    .weight(1f)           // Toplam 2 birimden 1 birim
                    .background(Color(0xFF1565C0))
                    .padding(12.dp)
            )
            Text(
                text = "Sağ (%50)",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                textAlign = TextAlign.Center,
                modifier = Modifier
                    .weight(1f)           // Toplam 2 birimden 1 birim
                    .background(Color(0xFF1976D2))
                    .padding(12.dp)
            )
        }

        Spacer(modifier = Modifier.height(16.dp))

        Text(
            text = "Oransal Paylaşım (2f + 1f)",
            fontSize = 13.sp,
            color = Color.Gray
        )
        Spacer(modifier = Modifier.height(4.dp))

        // İki bileşen farklı ağırlık: biri %66, diğeri %33 kaplar
        Row(modifier = Modifier.fillMaxWidth()) {
            Text(
                text = "Geniş (%66)",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                textAlign = TextAlign.Center,
                modifier = Modifier
                    .weight(2f)           // Toplam 3 birimden 2 birim
                    .background(Color(0xFF2E7D32))
                    .padding(12.dp)
            )
            Text(
                text = "Dar (%33)",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                textAlign = TextAlign.Center,
                modifier = Modifier
                    .weight(1f)           // Toplam 3 birimden 1 birim
                    .background(Color(0xFF388E3C))
                    .padding(12.dp)
            )
        }

        Spacer(modifier = Modifier.height(16.dp))

        Text(
            text = "Üçlü Paylaşım (1f + 2f + 1f)",
            fontSize = 13.sp,
            color = Color.Gray
        )
        Spacer(modifier = Modifier.height(4.dp))

        // Üç bileşen: ortadaki daha geniş
        Row(modifier = Modifier.fillMaxWidth()) {
            Text(
                text = "%25",
                fontSize = 13.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                textAlign = TextAlign.Center,
                modifier = Modifier
                    .weight(1f)
                    .background(Color(0xFFC62828))
                    .padding(12.dp)
            )
            Text(
                text = "%50",
                fontSize = 13.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                textAlign = TextAlign.Center,
                modifier = Modifier
                    .weight(2f)
                    .background(Color(0xFFE53935))
                    .padding(12.dp)
            )
            Text(
                text = "%25",
                fontSize = 13.sp,
                fontWeight = FontWeight.Bold,
                color = Color.White,
                textAlign = TextAlign.Center,
                modifier = Modifier
                    .weight(1f)
                    .background(Color(0xFFC62828))
                    .padding(12.dp)
            )
        }
    }
}

@Preview(showBackground = true)
@Composable
fun WeightTemelOrnegiOnizleme() {
    WeightTemelOrnegi()
}
```

---

## Sabit Boyut ve weight Birlikte Kullanımı

`weight()` modifier'ının en güçlü özelliklerinden biri, sabit boyutlu bileşenlerle birlikte kullanılabilmesidir. Sabit boyutlu bileşenler önce kendi alanlarını alır; geriye kalan boşluk ise `weight()` taşıyan bileşenler arasında paylaştırılır.

Bunu bir masaya oturmaya benzetmek mümkündür. Masanın iki ucuna sabit genişlikte sandalyeler yerleştirilir. Ortada kalan boşluk ise kanepeye ayrılır. Kanepe sabit bir genişliğe sahip değildir; masanın boyutuna göre otomatik olarak büyür ya da küçülür. `weight()` da bileşene tam olarak bu "esnek kanepe" davranışını kazandırır.

```kotlin
// MainActivity.kt

package com.example.weightSabit

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.AccountCircle
import androidx.compose.material.icons.filled.MoreVert
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            SabitVeWeight()
        }
    }
}

// Tipik bir liste satırı:
// Sol taraf sabit (ikon), orta esnek (isim + açıklama), sağ taraf sabit (ikon)
@Composable
fun KullaniciSatiri(isim: String, mesaj: String, saat: String) {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp, vertical = 10.dp),
        verticalAlignment = Alignment.CenterVertically
    ) {
        // Sol ikon: sabit boyut, weight almaz
        Icon(
            imageVector = Icons.Filled.AccountCircle,
            contentDescription = null,
            tint = Color(0xFF1565C0),
            modifier = Modifier.size(44.dp)
        )

        // Orta bölüm: weight(1f) ile kalan tüm alanı kaplar
        // Sol ve sağ ikonlar sabit alanlarını aldıktan sonra
        // geriye ne kadar alan kalırsa bu bölüm onu kullanır.
        Column(
            modifier = Modifier
                .weight(1f)
                .padding(horizontal = 10.dp)
        ) {
            Text(
                text = isim,
                fontSize = 15.sp,
                fontWeight = FontWeight.Bold
            )
            Text(
                text = mesaj,
                fontSize = 13.sp,
                color = Color.Gray,
                maxLines = 1
            )
        }

        // Sağ taraf: saat metni sabit, weight almaz
        Column(horizontalAlignment = Alignment.End) {
            Text(
                text = saat,
                fontSize = 12.sp,
                color = Color.Gray
            )
            Icon(
                imageVector = Icons.Filled.MoreVert,
                contentDescription = null,
                tint = Color.Gray,
                modifier = Modifier.size(18.dp)
            )
        }
    }
}

@Composable
fun SabitVeWeight() {
    Column {
        Text(
            text = "Mesajlar",
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold,
            modifier = Modifier.padding(16.dp)
        )
        KullaniciSatiri(
            isim = "Ali Yılmaz",
            mesaj = "Toplantı saat kaçta başlıyordu?",
            saat = "14:32"
        )
        KullaniciSatiri(
            isim = "Ayşe Kara",
            mesaj = "Projeyi inceledim, harika görünüyor!",
            saat = "11:15"
        )
        KullaniciSatiri(
            isim = "Mehmet Çelik",
            mesaj = "Yarın ofiste misin?",
            saat = "Dün"
        )
    }
}

@Preview(showBackground = true)
@Composable
fun SabitVeWeightOnizleme() {
    SabitVeWeight()
}
```

Sol ikon 44 dp, sağ taraftaki içerik kendi genişliğini alır. Ortadaki `Column` ise `weight(1f)` sayesinde geriye kalan tüm alanı kaplar. Ekran ne kadar geniş ya da dar olursa olsun orta bölüm buna otomatik olarak uyum sağlar.

---

## Column'da weight Kullanımı

`weight()` yalnızca `Row` içinde değil, `Column` içinde de kullanılabilir. Bu durumda alan yatay değil **dikey eksende** paylaştırılır.

```kotlin
// MainActivity.kt

package com.example.columnweight

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            ColumnWeightOrnegi()
        }
    }
}

@Composable
fun ColumnWeightOrnegi() {
    // fillMaxSize ile Column tüm ekranı kaplar.
    // weight bu alanı dikey eksende böler.
    Column(modifier = Modifier.fillMaxSize()) {

        // Üst alan: ekranın %20'sini kaplar
        Text(
            text = "Üst Bölüm (%20)",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .weight(1f)               // Toplam 5 birimden 1 birim
                .background(Color(0xFF1565C0))
                .padding(8.dp)
        )

        // Orta alan: ekranın %60'ını kaplar (ana içerik alanı)
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .weight(3f)               // Toplam 5 birimden 3 birim
                .background(Color(0xFFF5F5F5))
                .padding(16.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Text(
                text = "Ana İçerik Alanı (%60)",
                fontSize = 16.sp,
                fontWeight = FontWeight.Bold,
                color = Color(0xFF1A237E)
            )
            Text(
                text = "Bu alan ekranın büyük çoğunluğunu kaplar. " +
                       "weight(3f) değeri, bu bölüme diğerlerinin " +
                       "üç katı alan verir.",
                fontSize = 14.sp,
                color = Color.Gray,
                textAlign = TextAlign.Center
            )
        }

        // Alt alan: ekranın %20'sini kaplar
        Text(
            text = "Alt Bölüm (%20)",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .fillMaxWidth()
                .weight(1f)               // Toplam 5 birimden 1 birim
                .background(Color(0xFF1565C0))
                .padding(8.dp)
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ColumnWeightOrnegiOnizleme() {
    ColumnWeightOrnegi()
}
```

Toplam ağırlık 1 + 3 + 1 = 5'tir. Üst ve alt bölümler 5 birimden 1'er birim alır (her biri %20); ana içerik alanı 3 birim alır (%60). Ekran boyutu değişse de bu oranlar korunur.

---

## Özet

`weight()` modifier'ı `Row` ve `Column` içindeki bileşenlerin kalan alanı oransal olarak paylaşmasını sağlar. Yüksek `weight` değeri daha fazla alan, düşük değer daha az alan anlamına gelir. Sabit boyutlu bileşenlerle birlikte kullanıldığında sabit bileşenler önce kendi alanlarını alır, geri kalan boşluk `weight` taşıyan bileşenler arasında paylaştırılır. Bu yapı, farklı ekran boyutlarına otomatik olarak uyum sağlayan esnek düzenler oluşturmanın en etkili yoludur.

# Alıştırma: Farklı Hizalama Örnekleri

## Alıştırmanın Amacı

Bu alıştırmada `verticalArrangement`, `horizontalAlignment`, `horizontalArrangement`, `verticalAlignment` ve `weight()` kavramlarının tamamı tek bir uygulama içinde bir araya getirilir. Her bölüm farklı bir hizalama senaryosunu temsil eder ve gerçek uygulamalarda sıkça karşılaşılan düzenleri yansıtır.

Bunu bir mimarlık çizim tahtasına benzetmek mümkündür. Bu derste öğrenilen her hizalama kuralı ayrı bir çizim aracıydı. Bu alıştırma ise tüm araçları kullanarak eksiksiz bir bina planı çizmek gibidir.

---

## Ekranın Planı

Kod yazmadan önce ekranın hangi bölümlerden oluşacağı planlanır:

```
┌──────────────────────────────────┐
│  Üst Çubuk                       │  ← Row / SpaceBetween / CenterVertically
│  [Başlık]          [İkon İkon]   │
├──────────────────────────────────┤
│  Karşılama Bölümü                │  ← Column / Center / CenterHorizontally
│        [İkon]                    │
│      [Başlık]                    │
│     [Alt başlık]                 │
├──────────────────────────────────┤
│  İstatistik Satırı               │  ← Row / SpaceEvenly / CenterVertically
│  [Kutu]  [Kutu]  [Kutu]          │
├──────────────────────────────────┤
│  Ayarlar Listesi                 │  ← Row / SpaceBetween / CenterVertically
│  [İkon + Metin]      [Değer]     │
│  [İkon + Metin]      [Değer]     │
├──────────────────────────────────┤
│  Alt Düğme Satırı                │  ← Row / weight(1f) eşit paylaşım
│  [İptal]            [Kaydet]     │
└──────────────────────────────────┘
```

---

## Bölüm 1: Üst Çubuk

Üst çubukta sol tarafta başlık, sağ tarafta iki ikon yan yana durur. `SpaceBetween` başlığı sola, ikonları sağa yaslar. `CenterVertically` ise farklı yükseklikteki öğeleri dikey olarak ortalar.

```kotlin
// MainActivity.kt - Bölüm 1

package com.example.hizalamaalistirma

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.shadow
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            TamEkran()
        }
    }
}

// ── Renk Paleti ───────────────────────────────────────────────
private val lacivert = Color(0xFF1A237E)
private val mavi = Color(0xFF1565C0)
private val acikMavi = Color(0xFFE3F2FD)
private val yesil = Color(0xFF2E7D32)
private val acikYesil = Color(0xFFE8F5E9)
private val gri = Color(0xFF757575)
private val acikGri = Color(0xFFF5F5F5)

// ── Bölüm 1: Üst Çubuk ───────────────────────────────────────
@Composable
fun UstCubuk() {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .shadow(4.dp, RoundedCornerShape(12.dp))
            .background(Color.White, RoundedCornerShape(12.dp))
            .padding(horizontal = 16.dp, vertical = 12.dp),
        // SpaceBetween: başlık sola, ikonlar sağa yaslanır
        horizontalArrangement = Arrangement.SpaceBetween,
        // CenterVertically: farklı yükseklikteki öğeler ortaya hizalanır
        verticalAlignment = Alignment.CenterVertically
    ) {
        // Sol: başlık
        Text(
            text = "Hizalama Alıştırması",
            fontSize = 18.sp,
            fontWeight = FontWeight.Bold,
            color = lacivert
        )

        // Sağ: iki ikon, spacedBy ile aralarında sabit boşluk
        Row(
            horizontalArrangement = Arrangement.spacedBy(12.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Icon(
                imageVector = Icons.Filled.Search,
                contentDescription = "Ara",
                tint = mavi,
                modifier = Modifier.size(22.dp)
            )
            Icon(
                imageVector = Icons.Filled.Notifications,
                contentDescription = "Bildirimler",
                tint = mavi,
                modifier = Modifier.size(22.dp)
            )
        }
    }
}
```

---

## Bölüm 2: Karşılama Bölümü

Karşılama bölümünde ikon, başlık ve alt başlık üst üste ve tam ortada durur. `CenterHorizontally` tüm içeriği yatay olarak ortalar; `Center` ise dikey olarak ortalar.

```kotlin
// ── Bölüm 2: Karşılama Bölümü ────────────────────────────────
@Composable
fun KarsilamaBolumu() {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .background(acikMavi, RoundedCornerShape(16.dp))
            .padding(24.dp),
        // Center: içeriği dikey olarak ortalar
        verticalArrangement = Arrangement.Center,
        // CenterHorizontally: tüm bileşenler yatay olarak ortaya hizalanır
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Icon(
            imageVector = Icons.Filled.AccountCircle,
            contentDescription = null,
            tint = mavi,
            modifier = Modifier.size(64.dp)
        )

        Spacer(modifier = Modifier.height(10.dp))

        Text(
            text = "Merhaba, Ali!",
            fontSize = 22.sp,
            fontWeight = FontWeight.Bold,
            color = lacivert,
            // textAlign de içeriği metin içinde ortalar
            textAlign = TextAlign.Center
        )

        Spacer(modifier = Modifier.height(4.dp))

        Text(
            text = "Bugün 3 yeni görevin var.",
            fontSize = 14.sp,
            color = gri,
            textAlign = TextAlign.Center
        )
    }
}
```

---

## Bölüm 3: İstatistik Satırı

Üç istatistik kutusu yan yana dizilir. `SpaceEvenly` kutular arasındaki ve kenarlarındaki tüm boşlukları eşit tutar.

```kotlin
// ── Bölüm 3: İstatistik Satırı ───────────────────────────────
@Composable
fun IstatistikKutusu(sayi: String, etiket: String, arkaplan: Color, metin: Color) {
    Column(
        modifier = Modifier
            .background(arkaplan, RoundedCornerShape(10.dp))
            .padding(horizontal = 16.dp, vertical = 12.dp),
        // CenterHorizontally: sayı ve etiket her ikisi de ortada
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(
            text = sayi,
            fontSize = 22.sp,
            fontWeight = FontWeight.Bold,
            color = metin
        )
        Text(
            text = etiket,
            fontSize = 12.sp,
            color = metin.copy(alpha = 0.7f)
        )
    }
}

@Composable
fun IstatistikSatiri() {
    Row(
        modifier = Modifier.fillMaxWidth(),
        // SpaceEvenly: kutular arasındaki ve kenarlarındaki boşluklar eşit
        horizontalArrangement = Arrangement.SpaceEvenly,
        // CenterVertically: kutular aynı yükseklikte hizalanır
        verticalAlignment = Alignment.CenterVertically
    ) {
        IstatistikKutusu("12", "Görev", acikMavi, mavi)
        IstatistikKutusu("5", "Tamamlandı", acikYesil, yesil)
        IstatistikKutusu("7", "Bekleyen", Color(0xFFFFF8E1), Color(0xFFE65100))
    }
}
```

---

## Bölüm 4: Ayarlar Listesi

Her ayar satırında sol tarafta ikon ve metin, sağ tarafta değer bilgisi bulunur. `SpaceBetween` sol grubu sola, değeri sağa yaslar. `CenterVertically` ise tüm öğeleri dikey olarak ortalar.

```kotlin
// ── Bölüm 4: Ayar Satırı ─────────────────────────────────────
@Composable
fun AyarSatiri(ikon: ImageVector, baslik: String, deger: String) {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .padding(vertical = 10.dp),
        // SpaceBetween: sol grup sola, değer sağa yaslanır
        horizontalArrangement = Arrangement.SpaceBetween,
        // CenterVertically: ikon, metin ve değer aynı hizada
        verticalAlignment = Alignment.CenterVertically
    ) {
        // Sol grup: ikon ve metin yan yana
        Row(
            horizontalArrangement = Arrangement.spacedBy(10.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Icon(
                imageVector = ikon,
                contentDescription = null,
                tint = mavi,
                modifier = Modifier.size(20.dp)
            )
            Text(
                text = baslik,
                fontSize = 15.sp,
                color = Color(0xFF212121)
            )
        }

        // Sağ: değer metni
        Text(
            text = deger,
            fontSize = 14.sp,
            color = gri
        )
    }
}

@Composable
fun AyarlarBolumu() {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .shadow(2.dp, RoundedCornerShape(12.dp))
            .background(Color.White, RoundedCornerShape(12.dp))
            .padding(horizontal = 16.dp, vertical = 8.dp)
    ) {
        Text(
            text = "Ayarlar",
            fontSize = 14.sp,
            fontWeight = FontWeight.Bold,
            color = gri,
            modifier = Modifier.padding(vertical = 8.dp)
        )
        AyarSatiri(Icons.Filled.Language, "Dil", "Türkçe")
        HorizontalDivider(color = Color(0xFFEEEEEE))
        AyarSatiri(Icons.Filled.Palette, "Tema", "Açık")
        HorizontalDivider(color = Color(0xFFEEEEEE))
        AyarSatiri(Icons.Filled.Notifications, "Bildirimler", "Açık")
    }
}
```

---

## Bölüm 5: Alt Düğme Satırı

İki düğme yan yana eşit alanı paylaşır. `weight(1f)` her iki düğmeye de eşit genişlik verir. Ekran ne kadar geniş olursa olsun iki düğme her zaman yarımşar yer kaplar.

```kotlin
// ── Bölüm 5: Alt Düğme Satırı ────────────────────────────────
@Composable
fun AltDugmeSatiri() {
    Row(
        modifier = Modifier.fillMaxWidth(),
        horizontalArrangement = Arrangement.spacedBy(12.dp),
        verticalAlignment = Alignment.CenterVertically
    ) {
        // İptal düğmesi: weight(1f) ile alanın yarısını kaplar
        Text(
            text = "İptal",
            fontSize = 15.sp,
            fontWeight = FontWeight.Medium,
            color = mavi,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .weight(1f)
                .background(acikMavi, RoundedCornerShape(10.dp))
                .padding(vertical = 14.dp)
        )

        // Kaydet düğmesi: weight(1f) ile alanın diğer yarısını kaplar
        Text(
            text = "Kaydet",
            fontSize = 15.sp,
            fontWeight = FontWeight.Bold,
            color = Color.White,
            textAlign = TextAlign.Center,
            modifier = Modifier
                .weight(1f)
                .background(mavi, RoundedCornerShape(10.dp))
                .padding(vertical = 14.dp)
        )
    }
}
```

---

## Tüm Bölümleri Bir Araya Getirmek

```kotlin
// ── Tam Ekran ─────────────────────────────────────────────────
@Composable
fun TamEkran() {
    // verticalScroll: içerik uzun olduğunda kaydırılabilir olur
    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(acikGri)
            .verticalScroll(rememberScrollState())
            .padding(16.dp),
        // spacedBy: her bölüm arasına otomatik boşluk ekler
        verticalArrangement = Arrangement.spacedBy(16.dp)
    ) {
        // Bölüm 1: SpaceBetween + CenterVertically
        UstCubuk()

        // Bölüm 2: CenterHorizontally + Center
        KarsilamaBolumu()

        // Bölüm 3: SpaceEvenly + CenterVertically
        IstatistikSatiri()

        // Bölüm 4: SpaceBetween + CenterVertically
        AyarlarBolumu()

        // Bölüm 5: weight(1f) eşit paylaşım
        AltDugmeSatiri()
    }
}

// ── Önizleme ──────────────────────────────────────────────────
@Preview(showBackground = true)
@Composable
fun TamEkranOnizleme() {
    TamEkran()
}
```

---

## Her Bölümde Hangi Kavram Kullanıldı?

| Bölüm | Kullanılan Kavram | Değer |
|---|---|---|
| Üst Çubuk | `horizontalArrangement` | `SpaceBetween` |
| Üst Çubuk | `verticalAlignment` | `CenterVertically` |
| Karşılama | `horizontalAlignment` | `CenterHorizontally` |
| İstatistik | `horizontalArrangement` | `SpaceEvenly` |
| Ayarlar | `horizontalArrangement` | `SpaceBetween` |
| Düğmeler | `weight()` | `1f + 1f` eşit paylaşım |

---

## Özet

Bu alıştırmada `verticalArrangement`, `horizontalAlignment`, `horizontalArrangement`, `verticalAlignment` ve `weight()` kavramlarının tamamı farklı ekran bölümlerinde bir arada kullanılmıştır. Her bölüm kendine özgü bir hizalama ihtiyacını karşılamış ve bu ihtiyaca en uygun parametre seçilmiştir. Tüm bölümler tek bir `Column` içinde üst üste dizilerek tam ve çalışan bir ekran oluşturulmuştur.

