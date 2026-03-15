# 中原苑（nakaharaen） — プロジェクト指示書

## プロジェクト概要
- **クライアント**: 社会福祉法人 磯子コスモス福祉会 — 特別養護老人ホーム 中原苑
- **所在地**: 〒235-0036 神奈川県横浜市磯子区中原3-6-10
- **現行サイト**: nakaharaen.com（WordPress）
- **目的**: WordPress → 静的HTML/CSS/JS + JSON + PHP管理画面 でリニューアル
- **デプロイ先**: Xserver（本番）

## ディレクトリ構成
```
nakaharaen/
├── index.html              ← トップページ
├── css/
│   └── style.css           ← メインスタイルシート
├── js/
│   └── main.js             ← JSON読み込み＆動的表示
├── images/                 ← 画像・動画
│   ├── logo.png            ← ヘッダーロゴ
│   ├── hero.mp4            ← ヒーロー背景動画
│   ├── director.jpg        ← 理事長写真
│   └── *.jpeg / *.jpg      ← お知らせ用画像
├── data/
│   ├── availability.json   ← 空き情報データ（管理画面から更新）
│   └── news.json           ← お知らせデータ（管理画面から更新）
└── admin/
    ├── index.php           ← 管理画面（PHP版・Xserver本番用）
    ├── index.html          ← 管理画面（HTMLデモ版・GitHub Pages用）
    ├── save.php            ← JSON保存処理
    └── style.css           ← 管理画面用スタイル
```

## 技術スタック
- **HTML5 + CSS3**（ピュアCSS、フレームワーク不使用）
- **JavaScript（Vanilla）**: fetch() で JSON を読み込み動的表示
- **PHP**: 管理画面（Xserver上で動作）
- **Google Fonts**: Noto Sans JP（本文） + Noto Serif JP（見出し・明朝体）
- **アニメーション**: Intersection Observer API によるフェードイン

## デザイン仕様

### カラーパレット
| 用途 | カラーコード | 名称 |
|------|-------------|------|
| アクセントカラー | `#E8837C` | コーラルピンク |
| ヘッダー背景 | `#E8365C`（95%透過） | レッド |
| 電話番号ボタン | `#76BF26` | グリーン |
| テキスト | `#3A3A3A` | ダークグレー |
| 見出しテキスト | `#2D2D2D` | ほぼ黒 |
| 背景 | `#FFF8F5` | ソフトベージュ |
| カード背景 | `#FFFFFF` | 白 |

### フォント
- **本文**: `'Noto Sans JP', 'Hiragino Kaku Gothic ProN', sans-serif`
- **見出し**: `'Noto Serif JP', 'Yu Mincho', 'Hiragino Mincho ProN', serif`（明朝体）
- 見出し（h2、セクションタイトル）は必ず明朝体を使用すること

### レスポンシブ
- モバイルファースト設計
- ブレイクポイント: 768px / 1024px
- モバイルではハンバーガーメニュー

## トップページ セクション構成（上→下）
1. **ヘッダー**: ロゴ画像（logo.png）、ナビ（白文字）、電話番号（緑ボタン）、ハンバーガー
2. **ヒーロー**: 動画背景（hero.mp4）、お問い合わせボタン（右下配置）
3. **空き情報**: JSONから動的読み込み、カードUI
4. **ごあいさつ**: 理事長写真＋プロフィール
5. **サービス内容**: 5枚のカードグリッド（SVGアイコン付き）
6. **お知らせ**: JSONから動的読み込み、画像付きカードグリッド
7. **アクセス**: Googleマップ + 施設情報
8. **フッター**: 法人情報・サイトマップ

## 管理画面の仕様
- **認証**: PHP セッションベースログイン（パスワード: `nakaharaen2026`）
- **タブ切替**: 「空き情報」「お知らせ管理」
- **空き情報**: 数値入力 → availability.json 上書き保存 → サイトに即反映
- **お知らせ**: 追加・削除 → news.json 上書き保存 → サイトに即反映
- **GitHub Pages用**: admin/index.html（デモ版、データ保存なし）

## データ構造

### availability.json
```json
{
  "updatedAt": "2026-03-11",
  "services": [
    { "id": "day", "name": "地域密着型通所介護（デイサービス）", "slots": [{ "label": "男女問わず", "count": 14 }] },
    { "id": "short", "name": "短期入所生活介護（ショートステイ）", "slots": [{ "label": "男性", "count": 2 }, { "label": "女性", "count": 2 }] }
  ]
}
```

### news.json
```json
{
  "items": [
    { "id": 1, "date": "2026-02-13", "category": "お知らせ", "title": "...", "content": "...", "image": "images/xxx.jpg", "link": "/recruit" }
  ]
}
```

## 開発ルール
- **ローカルサーバー**: `python3 -m http.server 8080`（PHPは未インストール）
- **file:// では動作しない**: fetch() が CORS エラーになるため、必ず localhost 経由で確認
- **画像追加時**: news.json の該当アイテムの `image` フィールドにパスを手動で設定
- **お知らせの画像**: 将来的にPHP管理画面に画像アップロード機能を追加予定

## 未実装ページ（今後の作業）
- 施設紹介（/about）
- 各サービスページ（/service/tokuyou/, /service/yobou/, /service/day/, /service/short/）
- 講習案内（/school/jitsumusya/）
- 職員募集（/recruit）
- セラエクサ（/businessplan）
- 役員・評議員（/yakuin）
- お問い合わせフォーム（/form/）

## 注意事項
- クライアントの個人情報・施設利用者情報は絶対に含めない
- 管理画面パスワードは本番デプロイ前に変更すること
- hero.mp4 は約15MBあるためGit管理に注意
