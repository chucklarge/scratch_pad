package main

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"
)

type Request struct {
	Remote string `json:"remote"`
}

func sendRequest(command string) {
	var url = "http://192.168.1.8:3388/cgi-bin/toServerValue.cgi"
	fmt.Println("URL:>", url)

	r := Request{
		Remote: command,
	}
	jsonStr, err := json.MarshalIndent(r, "", "  ")
	if err != nil {
		panic(err)
	}
	//fmt.Printf("%s\n", jsonStr)

	req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
	req.Header.Set("Content-Type", "application/json")
	client := &http.Client{}

	resp, err := client.Do(req)
	if err != nil {
		panic(err)
	}
	defer resp.Body.Close()

	body, _ := ioutil.ReadAll(resp.Body)
	//fmt.Println("response Status:", resp.Status)
	//fmt.Println("response Headers:", resp.Header)
	//fmt.Println("response Body:", string(body))
	fmt.Printf("%s\t%s\t%s", command, resp.Status, string(body))
}

func main() {
	sendRequest("n")
}
