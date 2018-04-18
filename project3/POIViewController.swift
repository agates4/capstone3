//
//  POIViewController.swift
//  project3
//
//  Created by Aron Gates on 4/18/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

import UIKit
import Alamofire

class POIViewController: UITableViewController  {

    var fruits = ["Apple", "Apricot", "Banana", "Blueberry", "Cantaloupe", "Cherry", "Clementine", "Coconut", "Cranberry", "Fig", "Grape", "Grapefruit", "Kiwi fruit", "Lemon", "Lime", "Lychee", "Mandarine", "Mango", "Melon", "Nectarine", "Olive", "Orange", "Papaya", "Peach", "Pear", "Pineapple", "Raspberry", "Strawberry"]
    
    // MARK: - Table view data source
    
    override func numberOfSections(in tableView: UITableView) -> Int {
        return 1
    }
    
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return fruits.count
    }
    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "LabelCell", for: indexPath)
        
        let fruitName = fruits[indexPath.row]
        cell.textLabel?.text = fruitName
        cell.detailTextLabel?.text = "Delicious!"
        
        return cell
    }
    
}
