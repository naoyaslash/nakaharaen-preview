# 中原苑プロジェクト — よく使う操作

## ローカル開発サーバー起動
```bash
cd /Users/naoya/Desktop/AI-Workspace/02_projects/nakaharaen
python3 -m http.server 8080
```
→ http://localhost:8080 でトップページ確認
→ http://localhost:8080/admin/ で管理画面（HTMLデモ版）確認

## GitHub Pages にプレビュー公開
```bash
cd /Users/naoya/Desktop/AI-Workspace/02_projects/nakaharaen
git add -A
git commit -m "更新内容をここに書く"
git push origin gh-pages
```
→ https://naoyaslash.github.io/nakaharaen-preview/ で確認

## お知らせの画像を追加する手順
1. 画像ファイルを `images/` フォルダに配置
2. `data/news.json` を開く
3. 該当のお知らせアイテムの `"image"` フィールドにパスを入力
   - 例: `"image": "images/ファイル名.jpg"`
4. ローカルサーバーで表示確認
5. GitHub Pages にプッシュ

## お知らせを手動追加する手順
`data/news.json` の `items` 配列の先頭に新しいオブジェクトを追加:
```json
{
  "id": 5,
  "date": "2026-03-14",
  "category": "お知らせ",
  "title": "タイトル",
  "content": "内容テキスト",
  "image": "images/写真.jpg",
  "link": ""
}
```
- `id` は既存の最大値 + 1
- `category` は「お知らせ」または「イベント」
- `image` と `link` は空文字でもOK

## 空き情報を手動更新する手順
`data/availability.json` の `count` 値を変更:
- `updatedAt` を当日の日付に変更
- 各 `slots` の `count` を新しい数値に変更

## 本番デプロイ（Xserver）手順（予定）
1. nakaharaen フォルダ一式を Xserver にアップロード
2. admin/ フォルダに .htaccess で Basic 認証を設定
3. admin/index.php のパスワードを変更
4. DNS を Xserver に向ける

## ファイル編集ガイド

### ヘッダーの電話番号を変更
- `index.html` 内の `<a href="tel:045-776-3100"` と表示テキストを変更

### ヘッダーの色を変更
- `css/style.css` の `.site-header` の `background` の色コードを変更

### ヒーロー動画を差し替え
- `images/hero.mp4` を新しい動画ファイルで上書き（ファイル名を変える場合は index.html も修正）

### サービスカードの内容を変更
- `index.html` 内の `.service-card` セクションを直接編集

### Googleマップの埋め込みを修正
- `index.html` 内の `<iframe src="...">` の URL を変更
